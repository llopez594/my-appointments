<?php namespace App\Services;

use App\Interfaces\ScheduleServiceInterface;
use App\WorkDay;
use Carbon\Carbon;

class ScheduleService implements ScheduleServiceInterface
{
    private function getDayfromDate($date)
    {
        $dateCarbon = new Carbon($date);

        $dayCarbon = $dateCarbon->dayOfWeek;
        $day = (($dayCarbon == 0) ? 6 : $dayCarbon-1);

        return $day;
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

        if(!$workDay) {
            return [];
        }

        $morningIntervals = $this->getIntervals(
            $workDay->morning_start, $workDay->morning_end
        );

        $afternoonIntervals = $this->getIntervals(
            $workDay->afternoon_start, $workDay->afternoon_end
        );

        $data = [];
        $data['morning'] = $morningIntervals;
        $data['afternoon'] = $afternoonIntervals;
    }

    private function getIntervals($start, $end)
    {
        $start = new Carbon($start);
        $end   = new Carbon($end);

        $intervals = [];
        while($start < $end) {
            $interval = [];
            $interval['start'] = $start->format('g:i A');
            $start->addMinutes(30);
            $interval['end'] = $start->format('g:i A');

            $intervals []= $interval;
        }
        return $intervals;
    }
}
