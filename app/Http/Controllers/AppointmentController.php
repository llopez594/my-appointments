<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\CancelledAppointments;
use App\Http\Requests\StoreAppointment;
use App\Interfaces\ScheduleServiceInterface;
use App\Specialty;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;

        //admin -> all
        if($role == 'admin')
        {
            $confirmedAppointments = Appointment::where('status','Confirmada')
                ->paginate(10);
            $pendingAppointments = Appointment::where('status','Reservada')
                ->paginate(10);
            $oldAppointments = Appointment::whereIn('status', ['Atendida', 'Cancelada'])
                ->paginate(10);
        }
        elseif($role == 'doctor')
        {
            $confirmedAppointments = Appointment::where('status','Confirmada')
                ->where('doctor_id', Auth::id())
                ->paginate(10);
            $pendingAppointments = Appointment::where('status','Reservada')
                ->where('doctor_id', Auth::id())
                ->paginate(10);
            $oldAppointments = Appointment::whereIn('status', ['Atendida', 'Cancelada'])
                ->where('doctor_id', Auth::id())
                ->paginate(10);
        }
        elseif($role == 'patient')
        {
            $confirmedAppointments = Appointment::where('status','Confirmada')
                ->where('patient_id', Auth::id())
                ->paginate(10);
            $pendingAppointments = Appointment::where('status','Reservada')
                ->where('patient_id', Auth::id())
                ->paginate(10);
            $oldAppointments = Appointment::whereIn('status', ['Atendida', 'Cancelada'])
                ->where('patient_id', Auth::id())
                ->paginate(10);
        }

        return view('appointments.index', compact('pendingAppointments', 'confirmedAppointments', 'oldAppointments', 'role'));
    }

    public function show(Appointment $appointment)
    {
        $role = auth()->user()->role;
        return view('appointments.show', compact('appointment', 'role'));
    }

    public function create(ScheduleServiceInterface $scheduleService)
    {
        $specialties = Specialty::all();

        $specialtiyId = old('specialty_id');
        if($specialtiyId) {
            $specialty = Specialty::find($specialtiyId);
            $doctors = $specialty->users;// selecciona los medicos asociados a la especialidad
        } else {
            $doctors = collect();
        }

        $date = old('schedule_date');
        $doctorId = old('doctor_id');
        if($date && $doctorId) {
            $intervals = $scheduleService->getAvailableIntervals($date, $doctorId);
        } else {
            $intervals = null;
        }

        return view('appointments.create', compact('specialties', 'doctors', 'intervals'));
    }

    public function store(StoreAppointment $request)
    {
        $created = Appointment::createForPatient($request, Auth::id());

        if($created)
            $notification = 'La cita se ha registrado correctamente!';
        else
            $notification = 'Ocurrio un problema al registrar la cita medica.';
        return back()->with(compact('notification'));
//        return redirect('/appointments');
    }

    public function showCancelForm(Appointment $appointment)
    {
        if($appointment->status == 'Confirmada') {
            $role = auth()->user()->role;
            return view('appointments.cancel', compact('appointment', 'role'));
        }
        return redirect('/appointments');
    }

    public function postCancel(Appointment $appointment, Request $request)
    {
        if($request->has('justification')) {
            $cancellation = new CancelledAppointments();
            $cancellation->justification = $request->input('justification');
            $cancellation->cancelled_by = Auth::id();

            $appointment->cancellation()->save($cancellation);
        }
        $appointment->status = "Cancelada";
        $saved = $appointment->save(); // update

        if ($saved)
            $appointment->patient->sendFCM('Su cita ha sido cancelada!.');

        $notification = "La cita se ha cancelado correctamente.";
        return redirect('/appointments')->with(compact('notification'));
    }

    public function postConfirm(Appointment $appointment)
    {
        $appointment->status = "Confirmada";
        $saved = $appointment->save(); // update

        if ($saved)
            $appointment->patient->sendFCM('Su cita se ha confirmado!.');

        $notification = "La cita se ha confirmado correctamente.";
        return redirect('/appointments')->with(compact('notification'));
    }

    private function performValidation(Request $request)
    {
        $rules = [
            'description' => 'required',
            'specialty_id' => 'exists:specialties,id',
            'doctor_id' => 'exists:users,id',
            'scheduled_time' => 'required'
        ];
        $messages = [
            'scheduled_time.required' => 'Por favor seleccione una hora valida para su cita.'
        ];
        $this->validate($request, $rules, $messages);
    }
}
