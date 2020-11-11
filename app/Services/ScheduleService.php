<?php namespace App\Services;

use App\Appointment;
use App\Interfaces\ScheduleServiceInterface;
use App\WorkDay;
use Carbon\Carbon;

class ScheduleService implements ScheduleServiceInterface
{
    public function isAvailableInterval($date, $doctorId, Carbon $start)
    {
        $exists = Appointment::where('doctor_id', $doctorId)
            ->where('schedule_date', $date)
            ->where('scheduled_time', $start->format('H:i:s'))
            ->exists();

        return !$exists; // available id already none exists.
    }

    public function getAvailableIntervals($date, $doctorId)
    {
        $workDay = WorkDay::where('active', true)
            ->where('day', $this->getDayfromDate($date))
            ->where('user_id', $doctorId)
            ->first([
                'morning_start', 'morning_end',
                'afternoon_start', 'afternoon_end'
            ]);

        if($workDay) {

            $morningIntervals = $this->getIntervals(
                $workDay->morning_start, $workDay->morning_end,
                $date, $doctorId
            );

            $afternoonIntervals = $this->getIntervals(
                $workDay->afternoon_start, $workDay->afternoon_end,
                $date, $doctorId
            );
        } else {
            $morningIntervals = [];
            $afternoonIntervals = [];
        }

        $data = [];
        $data['morning'] = $morningIntervals;
        $data['afternoon'] = $afternoonIntervals;
        return $data;
    }

    private function getDayfromDate($date)
    {
        $dateCarbon = new Carbon($date);

        $dayCarbon = $dateCarbon->dayOfWeek;
        $day = (($dayCarbon == 0) ? 6 : $dayCarbon-1);

        return $day;
    }

    private function getIntervals($start, $end, $date, $doctorId)
    {
        $start = new Carbon($start);
        $end   = new Carbon($end);

        $intervals = [];
        while($start < $end) {
            $interval = [];
            $interval['start'] = $start->format('g:i A');

            //evalua si existe una hora reservada para un medico.
            $available = $this->isAvailableInterval($date, $doctorId, $start);

            $start->addMinutes(30);
            $interval['end'] = $start->format('g:i A');


            if($available){
                $intervals []= $interval;
            }
        }
        return $intervals;
    }
}
