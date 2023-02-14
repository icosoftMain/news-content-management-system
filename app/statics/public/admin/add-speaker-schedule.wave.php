<wv-admin-main crumpTitle={{#$crumpTitle#}} {crumpLink} crumpSlashTitle={/&nbsp;{#$crumpTitle#}}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <a href="{# url(':manage_speakers') #}">&laquo; Go Back to Speaker's Details</a>
        <a href="{# url($schedTypeLink) #}">&nbsp;&nbsp; {# $schedTypeLabel #}</a>
        <wv-comp.speakerImage 
            W={20}
            H={200}
            imageName={images/speakers_pics/{$speakerImageName}}
            imageTitle={{#$speakerImageTitle#}}
        />
        <form action="#!" name="speaker_schedule_form" id="speaker_schedule_form" method="post" class="form-horizontal form-material">
            <br><br><br/>
            @each $availableScheds as $eventName => $scheds:   
               <wv-comp.tables.speaker-schedules 
                    spSchedId={tbl-admin-speaker-event-schedule}
                    eventTotal={count($scheds)}
                    type={admin}
                    {eventName}
                    {scheds}
               />
                <br><br>
            @endeach
            @if count($availableScheds) <> 0 && $scheduleType === 'unassigned':
                <div class="form-group">
                    <div style="text-align: center;" class="col-md-12">
                        <button id="btn-assign-sched" class="btn btn-primary" name="assign" >Assign Schedule</button>
                    </div>
                </div>
                @elif count($availableScheds) === 0:
                    <h2 style="text-align: center; color: #777777;">{#$emptySchedule#}{#$speakerFullName#}</h2>
            @endif
            <input type="hidden" id="speakerId" value="{# $speakerId #}"/>
            {# @CSRF #}   
        </form>
        @if $scheduleType === 'assigned':
            <wv-comp.modal 
                deleteModal
                modalId={deleteAssignedScheduleModal}
                footerComponent={
                    <button class="btn btn-primary" id="delete_assigned_schedule">Yes</button>&nbsp;
                    <button class="btn btn-custom modal-close" data-dismiss="modal">No</button>
                }
            >
                <div style="font-size: 22px;">
                    <i class="fa fa-warning red-text"></i>
                    Are you sure you want to delete this schedule ?
                </div>
            </wv-comp.modal>
        @endif
    </wv-comp.admin-white-box>
</wv-admin-main>