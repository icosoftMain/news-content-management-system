@if {:str.type} === 'admin':
    {! $scheds =  json_encode($scheds) !}
    {! $scheds = ((array)json_decode($scheds));!}
@else:
    {! $scheds = ((array)json_decode('{:val.scheds}'));!}
@endif
<table id="{:val.spSchedId}" class="table">
    <thead>
        <tr class="centerit">
            <th>Event Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            @if {:str.type} === 'admin': <th>{#$actionType#}</th> @endif
        </tr>
    </thead>
    <tbody>
    @each  $scheds as $key => $sched:
        <tr>
            @if $key === 0:
                <td 
                style="
                    vertical-align: middle; 
                    font-size: 18px; 
                    font-weight: bold; 
                    background: #707cd2;
                    color: #fff;
                    text-align: justify;
                "
                rowspan="{#{:val.eventTotal}#}">
                    {#{:val.eventName}#}
                </td>
            @endif
            <td>{# dateQuery($sched->startDate,'D, d-M-Y') #}</td>
            <td>{# dateQuery($sched->endDate,'D, d-M-Y') #}</td>
            <td>{# dateQuery($sched->startTime,'h:i A') #}</td>
            <td>{# dateQuery($sched->endTime,'h:i A') #}</td>
            <td class="center-child">
                @if $scheduleType === 'unassigned' && {:str.type} === 'admin':
                    <input class="periods" type="checkbox" name="periods_{# $sched->timeId #}"  value="{# $sched->timeId #}"/>
                    @elif {:str.type} === 'admin':
                        <input type="hidden" class="liveEventId_token" value="{# $sched->liveEventId #}"/>
                        <a href="#!" class="delete_assigned_schedule" title="Delete Assigned Schedule" data-toggle="modal" data-target="#deleteAssignedScheduleModal"><i class="fa fa-trash"></i></a>
                @endif
            </td>
        </tr>
    @endeach
    </tbody>
</table>
