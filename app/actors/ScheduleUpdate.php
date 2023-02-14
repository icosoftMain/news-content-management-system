<?php namespace App\Actors;

class ScheduleUpdate extends EventSchedules {
 
	protected function error_report():array
	{
		self::$updated = true;

		return [
			'startDate:?date'     => 'Please provide the start date of the event',
            'endDate:?date'       => 'Please provide the end date of the event',
            'startTime:?time'     => 'Please provide the start time of the event',
            'endTime:?time'       => 'Please provide the end time of the event'
		];
	}
}