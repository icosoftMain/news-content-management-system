<?php namespace App\Controllers\Events;

use App\Models\ilapi_pro_cms\DS\{
    DsEvent,
    DsEventTime
};
use FLY\Libs\Request;
use FLY\MVC\Model;
use FLY\Routers\Redirect;

trait Schedules {

    static function addSchedule(Request $request) 
    {
        if($request::exists('event')) {
            $evmodel = DsEvent::fetch(':eventName{1}',new DsEvent('',$request->event));
            if(isset($evmodel->eventId[0]['eventId'])) {
                self::show();
                self::add_context(self::getRequestReports());
                self::$state['reqValues'] = self::$sessions::exists('reports') ? self::$sessions::get('reports')['data'] : [];
                self::$state['eventName'] = $request->event;
                self::$state['eventId']   = $evmodel->eventId[0]['eventId'];
                self::add_context(self::$state);
                return;
            }
        }
        Redirect::to(url(':manage_events'));
    }

    static function editSchedule(Request $request)
    {
        if(Request::exists('schedule')) {
            self::$sessions::set('scheduleId','?schedule='.$request->schedule);
            $model = DsEventTime::get($request->schedule);
			self::authRequest($model->timeId,':manage_events');
            self::$state['startDate'] = $model->startDate;
            self::$state['endDate']   = $model->endDate;
            self::$state['startTime'] = $model->startTime;
            self::$state['endTime']   = $model->endTime;
            self::$state['timeId']    = $model->timeId;
            self::show();
            self::add_context(self::getRequestReports());
            self::add_context(['reqValues' => self::$state,'eventId' => $model->eventId]);
        } else self::authRequest('',':manage_events');
    }

    public static function getCurrAssignedSchedules($speakerId)
    {
        $assignedSched = Model::query("CALL curr_assigned_schedules('{$speakerId}')");
        return self::arrangePayload($assignedSched,'eventName');
    }

    static private function fetchCurrentSchedules($speakerId)
    {
        $currentSchedule = Model::query("CALL available_schedules('{$speakerId}')");
        return self::arrangePayload($currentSchedule,'eventName');
    }

    static public function arrangePayload($currDt,$index)
    {
        $data = [];
        foreach($currDt as $dt) {
            $value = trim($dt[$index]);
            if(!array_key_exists($value,$data)) $data[$value] = [];
            
            unset($dt[$index]);
            array_push($data[$value],$dt);
        }
        return $data;
    }
}