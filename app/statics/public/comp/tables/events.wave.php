<table id="tbl-admin-event" class="table border">
    <thead>
        <tr class="right_align"></tr>
        <tr class="centerit">
            <th>Event Poster</th>
            <th>Event Name</th>
            <th class="center-child">Published</th>
            <th class="center-child">Add Schedule</th>
            <th class="center-child">Edit Schedule</th>
            <th class="center-child">Edit Event</th>
            <th class="center-child">Delete Event</th>
        </tr>
    </thead>
    <tbody>
        @each $events as $key => $event:
            @if $key === 'pagLim': @thenend @endif
            <tr>
                <td>
                    <img 
                        class="table-pic" 
                        src="@statics('images/events/'.$event['eventPoster'])"
                    /> 
                </td>

                <td>{# $event['eventName'] #}</td>
              
                <td class="center-child">{# $event['published'] #}</td>
                <td class="center-child">
                    <a href="{# url(':add_schedule?event='.$event['eventName']) #}" class="add_event_schedule" title="Add Schedule"><i class="fa fa-calendar"></i><i class="fa fa-plus"></i></a>
                </td>
                <td class="center-child">
                    <a href="#!" class="edit_event_schedule" title="Edit Schedule" data-toggle="modal" data-target="#{# $event['eventName'] #}"><i class="fa fa-calendar"></i><i class="fa fa-edit"></i></a>
                </td>
                <td class="center-child">
                    <a href="{# url(':edit_event?event='.$event['eventId']) #}" class="edit_event" title="Edit"><i class="fa fa-edit"></i></a>
                </td>
                <td class="center-child">
                    <input type="hidden" class="eventId_token" value="{# $event['eventId'] #}"/>
                    <a href="#!" class="delete_event" title="Delete" data-toggle="modal" data-target="#deleteEventModal"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        @endeach
    </tbody>
</table>

<wv-comp.modal 
    deleteModal
    modalId={deleteEventModal}
    footerComponent={
        <button class="btn btn-primary" id="delete_event">Yes</button>&nbsp;
        <button class="btn btn-custom modal-close" data-dismiss="modal">No</button>
    }
>
    <div style="font-size: 22px;">
        <i class="fa fa-warning red-text"></i>
        Are you sure you want to delete this event ?
    </div>
</wv-comp.modal>

@each $events as $event:
    <wv-comp.staticModal 
        TargetId={{# $event['eventName'] #}}
        Title={
            <fml_fragment>
                <i class="fa fa-calendar"></i><i class="fa fa-edit"></i>
                Current Event Schedules
            </fml_fragment>
        }
    >
        <wv-comp.tables.event-schedule 
            id={tbl-admin-event-schedule} 
            type={admin}
            eventId={$event['eventId']} eventName={{# $event['eventName'] #}}
        />
    </wv-comp.staticModal>
@endeach

<wv-comp.modal
    deleteModal
    modalId={deleteScheduleModal}
    footerComponent={
        <button class="btn btn-primary" id="delete_schedule">Yes</button>&nbsp;
        <button class="btn btn-custom modal-close" data-dismiss="modal">No</button>
    }
>
    <div style="font-size: 22px;">
        <i class="fa fa-warning red-text"></i>
        Are you sure you want to delete this schedule ?
    </div>
</wv-comp.modal>