<?php namespace App\Actors;
use FLY\Security\{KeyGen,Sessions};
use App\Models\ilapi_pro_cms\DS\{DsEvent,DsEventTime};
use App\Actors\File_API\{ UploadImage, File };

class SetEvent extends FormValidator {

    private $eventNameExists = false;

    public function create(array $requestData)
    {
        if($this->validator->has_error()) {
            $this->reports = $this->validator->get_error_message();
        } else if(!$this->eventExists()) {

            if(!$this->eventNameExists) {
               $this->save_new_event();
            }
            else { 
               $this->save_existing_event();
            }
            $this->reports['state']   = true;
            $this->reports['payload'] = 'New event was added successfully';
        } else {
            $this->reports['state'] = false;
            $this->reports['payload'] = "This event name '{$this->request->eventName}' already exist.";
        }
        $this->saveRequestData($requestData);
        Sessions::set('reports', $this->reports);
    }

    private function save_existing_event()
    {
        $md = DsEvent::set_request($this->request)::fetch(':eventName{1}');
        $eventId = $md->eventId[0]['eventId'];
        $this->request::add('eventId',$eventId);
        $this->request::add('timeId',KeyGen::primaryKeys(12,'ET%key',true));
        DsEventTime::save_request($this->request);
    }

    private function save_new_event() 
    {
        if(File::is_present('eventImage') && $this->imageUploaded()) {
            $this->add_event();
        } else {
            $this->reports['state'] = false;
            $this->reports['payload'] = 'Please provide the image of the event.';
        }
    }

    public function delete()
    {
        $md = DsEvent::get($this->request->eventId);
        $eventName = $md->eventName;
        if(File::exists("app/statics/images/events/{$md->eventPoster}")) {
            File::remove("app/statics/images/events/{$md->eventPoster}");
        }
        $md->delete();
        Sessions::set('reports', ['state' => true, 'payload' => "{$eventName} event was successfully deleted."]);
        return ['state' => true];
    }

    private function add_event()
    {
        $this->request::add('eventId',KeyGen::primaryKeys(12,'E%key',true));
        $this->request::add('eventPoster',$this->reports['filename']);
        $this->request::add('timeId',KeyGen::primaryKeys(12,'ET%key',true));
        DsEvent::save_request($this->request,$this->model);
        DsEventTime::save_request($this->request);
    }

    private function eventExists()
    {
        $model = DsEvent::set_request($this->request);
        $model = $model::fetch(':eventName{1}');
        $this->eventNameExists = isset($model->eventId[0]['eventId']);
        return $this->eventNameExists && $this->eventTimeIsCurrent($model->eventId[0]['eventId']);
    }

    private function eventTimeIsCurrent(string $eventId)
    {
        $eventTimes = DsEventTime::fetch(':eventId',new DsEventTime('',$eventId));
        $endDates   = $eventTimes->endDate;
        $flag = false;
        foreach($endDates as $ed) {
            $eventYear  = (int) dateQuery($ed['endDate'],'Y');
            $eventMonth = (int) dateQuery($ed['endDate'],'M');
            if((int) thisYear() <= $eventYear && (int) thisMonth() <= $eventMonth) {
                $flag = true;
            break;
            }
        }
        return $flag;
    }

    private function imageUploaded()
    {
        $upload = new UploadImage('events/');
		$this->reports = $upload->upload_file('eventImage');
		return $this->reports['state'];
    }

    protected function error_report(): array
    {
        return array(
            'eventName:text'     => 'Please enter your event name',
            '_location:alpha'    => 'Event location should be alphebitcal',
            'startDate:date'     => 'Please provide the start date of the event',
            'endDate:date'       => 'Please provide the end date of the event',
            'startTime:time'     => 'Please provide the start time of the event',
            'endTime:time'       => 'Please provide the end time of the event',
            '_description:text'  => 'Please provide the description of the event',
            'published:(Y,N)'    => 'Please select yes or no to decide to publish your event',
            'eventType:alpha'    => 'Please select one of the event type (\'normal\',\'internship\',\'ajoet\')'
        );
    }
}