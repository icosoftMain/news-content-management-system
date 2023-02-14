<?php namespace App\Actors;
use FLY\Security\Sessions;
use App\Models\ilapi_pro_cms\DS\{DsEvent};

use FLY\Libs\{ Validator };

use App\Actors\File_API\{ File, UploadImage };

class UpdateEvent extends Validator {

	public function update()
	{
		if($this->validator->has_error()) {
			$this->response = $this->validator->get_error_message();
		} else {
			$file_flag = false;
			if(File::is_present('eventImage') && $this->imageUploaded()) {
                $this->removeCurrentImage();
                $this->editEventImage();
            } else if(File::is_present('eventImage')) {
                $file_flag = true;
			}
			if(!$file_flag) {
				$this->setUpdate();
                $this->response['state'] = true; 
                $this->response['payload'] = "Event '{$this->request->eventName}' was successfully updated";
            }
		}

		Sessions::set('reports',$this->response);
	}

	private function removeCurrentImage()
    {
		$model = DsEvent::set_request($this->request)::fetch(':eventId');
        if(isset($model->eventId[0]['eventId'])) {
			$imageName = $model->eventPoster[0]['eventPoster'];
            File::remove("app/statics/images/events/{$imageName}");
        }
	}

	private function imageUploaded()
    {
        $upload = new UploadImage('events/');
		$this->response = $upload->upload_file('eventImage');
		return $this->response['state'];
    }

    private function editEventImage()
    {
        if(!is_empty($this->request->eventId)) {
            $model = DsEvent::get($this->request->eventId);
            $model->eventPoster = $this->response['filename'];
            $model->edit();
        }
	}
	
	private function setUpdate()
	{
		DsEvent::edit_request($this->request);
	}

	protected function error_report(): array
	{
		return [
            'eventName:?text'     => 'Please enter your event name',
            '_location:?alpha'    => 'Event location should be alphebitcal',
            'startDate:?date'     => 'Please provide the start date of the event',
            'endDate:?date'       => 'Please provide the end date of the event',
            '_description:?text'  => 'Please provide the description of the event',
            'published:?alpha'    => 'Please select yes or no to decide to publish your event',
            'eventType:?alpha'    => 'Please select one of the event type (\'normal\',\'internship\',\'ajoet\')'
		];
	}
}