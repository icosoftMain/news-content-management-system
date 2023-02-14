<?php namespace App\Controllers\Packs;

use App\Models\ilapi_pro_cms\DS\DsEventTime;

trait Shares {

    static private function _currentEventSchedule()
    {
        $schedules = DsEventTime::all();
        $outSchedules = [];

        foreach($schedules as $scds) {
            $eventYear  = (int) dateQuery($scds['endDate'],'Y');
            $eventMonth = (int) dateQuery($scds['endDate'],'M');

            if($eventYear >= (int)thisYear() && $eventMonth >= (int)thisMonth())
                array_push($outSchedules,$scds);
        }
        return $outSchedules;
    }
}