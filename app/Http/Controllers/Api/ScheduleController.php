<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\ScheduleServiceInterface;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function hours(Request $request, ScheduleServiceInterface $scheduleService)
    {
//        dd($request->all());
        $this->performValidation($request);

        $date = $request->input('date');
        $doctorId = $request->input('doctor_id');

        return $scheduleService->getAvailableIntervals($date, $doctorId);
    }

    private function performValidation(Request $request)
    {
        $rules = [
            'date' => 'required|date_format:"Y-m-d"',
            'doctor_id' => 'required|exists:users,id'
        ];
        $this->validate($request, $rules);
    }
}
