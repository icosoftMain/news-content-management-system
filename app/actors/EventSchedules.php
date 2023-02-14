<?php namespace App\Actors;

use App\Models\ilapi_pro_cms\DS\{DsEvent, DsEventTime};
use FLY\Security\KeyGen;
use FLY\Security\Sessions;

class EventSchedules extends FormValidator {

	protected static $updated = false;


	public function save(array $requestData)
	{
		if($this->validator->has_error()) {
			$this->reports = $this->validator->get_error_message();
		} else if($this->eventTimeIsCurrent()) {
			
			$eventName = DsEvent::get($this->request->eventId)->eventName;
			$payload = "New schedule has been added to Event ({$eventName}) successfully.";
			if(!self::$updated) 
				$this->createSchedule();
			else {
				$this->editSchedule();
				$payload = "'{$eventName}' event schedule has been updated successfully.";
			}
			$this->reports = [
				'state'    => true,
				'payload'  => $payload
			];
		} else {
			$this->reports = [
				'state'   => false,
				'payload' => "Unable to add schedule: The schedule you just set was below the current date or start time is set above end time"
			];
		}
		
		$this->saveRequestData($requestData);
        Sessions::set('reports', $this->reports);
	}

	private function createSchedule()
	{
		$this->request::add('timeId',KeyGen::primaryKeys(12,'ET%key',true));
		DsEventTime::save_request($this->request);
	}

	private function editSchedule()
	{
		DsEventTime::edit_request($this->request);
	}

	public function delete()
	{
		DsEventTime::get($this->request->timeId)->delete();
		$this->response = ['state' => true];
		Sessions::set('reports',[
			'state'    => true,
			'payload'  => "Schedule was successfully deleted."
		]);
		return $this->response;
	}

	private function eventTimeIsCurrent()
	{
		$nowDate  = dateQuery("now",'Y-m-d');
        return (
			$this->request->startDate <= $this->request->endDate &&
			$this->request->startDate >= $nowDate                &&
			$this->request->endDate   >= $nowDate                &&
			$this->request->startTime <  $this->request->endTime
		);
	}

	protected function error_report():array
	{
		return [
			'startDate:date'     => 'Please provide the start date of the event',
            'endDate:date'       => 'Please provide the end date of the event',
            'startTime:time'     => 'Please provide the start time of the event',
            'endTime:time'       => 'Please provide the end time of the event'
		];
	}
}