<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Interfaces\ScheduleServiceInterface;
use App\Specialty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
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

        $scheduledDate = old('scheduled_date');
        $doctorId = old('doctor_id');
        if($scheduledDate && $doctorId) {
            $intervals = $scheduleService->getAvailableIntervals($scheduledDate, $doctorId);
        } else {
            $intervals = null;
        }

        return view('appointments.create', compact('specialties', 'doctors', 'intervals'));
    }

    public function store(Request $request)
    {
        $this->performValidation($request);

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
