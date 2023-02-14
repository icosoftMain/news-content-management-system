@if {:str.type} === 'admin': <h5>Event Name: {:val.eventName}</h5> @endif
<table id="{:val.id}" class="table table-hover table-dark">
  <thead>
    <tr>
      <th scope="col">Start Date</th>
      <th scope="col">End Date</th>
      <th scope="col">Start Time</th>
      <th scope="col">End Time</th>
      @if {:str.type} === 'admin': <th colspan="2">Actions</th> @endif
    </tr>
  </thead>
  <tbody>
    @each $eventSchedules as $escds:
        @if {:val.eventId} === $escds['eventId']:
            <tr>
                <td>{# dateQuery($escds['startDate'],'D, d-M-Y') #}</td>
                <td>{# dateQuery($escds['endDate'],'D, d-M-Y') #}</td>
                <td>{# dateQuery($escds['startTime'],'h:i A') #}</td>
                <td>{# dateQuery($escds['endTime'],'h:i A') #}</td>
                @if {:str.type} === 'admin':
                  <td class="center-child">
                      <a href="{# url(':edit_schedule?schedule='.$escds['timeId']) #}" class="edit_schedule" title="Edit"><i class="fa fa-edit"></i></a>
                  </td>
                  <td class="center-child">
                      <input type="hidden" class="timeId_token" value="{# $escds['timeId'] #}"/>
                      <a href="#!" class="delete_schedule" title="Delete" data-toggle="modal" data-target="#deleteScheduleModal"><i class="fa fa-trash"></i></a>
                  </td>
                @endif
            </tr>
        @endif
    @endeach
  </tbody>
</table>
