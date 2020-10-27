<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\CancelledAppointments;
use App\Interfaces\ScheduleServiceInterface;
use App\Specialty;
use Carbon\Carbon;
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

    public function store(Request $request, ScheduleServiceInterface $scheduleService)
    {
//        $this->performValidation($request);
        $rules = [
            'description' => 'required',
            'specialty_id' => 'exists:specialties,id',
            'doctor_id' => 'exists:users,id',
            'scheduled_time' => 'required'
        ];
        $messages = [
            'scheduled_time.required' => 'Por favor seleccione una hora valida para su cita.'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request, $scheduleService) {
            $date = $request->input('schedule_date');
            $doctorId = $request->input('doctor_id');
            $scheduled_time = $request->input('scheduled_time');
            if($date and $doctorId and $scheduled_time){
                $start = new Carbon($scheduled_time);
            } else {
                return;
            }

            if(!$scheduleService->isAvailableInterval($date, $doctorId, $start)) {
                $validator->errors()
                    ->add('available_time', 'La hora asignada ya se encuenta reservada por otro paciente');
            }
        });

        if($validator->fails()) {
            return back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $data = $request->only([
            'description',
            'specialty_id',
            'doctor_id',
            'schedule_date',
            'scheduled_time',
            'type'
        ]);
        $data['patient_id'] = Auth::id();
        //sobreescribimos scheduled_time para convertirlo a formato 24hrs
        $carbonTime = Carbon::createFromFormat('g:i A', $data['scheduled_time']);
        $data['scheduled_time'] = $carbonTime->format('H:i:s');

        Appointment::create($data);

        $notification = 'La cita se ha registrado correctamente!';
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
        $appointment->save(); // update

        $notification = "La cita se ha cancelado correctamente.";
        return redirect('/appointments')->with(compact('notification'));
    }

    public function postConfirm(Appointment $appointment)
    {
        $appointment->status = "Confirmada";
        $appointment->save(); // update

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
