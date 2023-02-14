<wv-admin-main crumpTitle={Event Schedule Form} {crumpLink} crumpSlashTitle={/&nbsp;Event Schedule Form}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <form action="{# $formType === 'edit' ? url(':updateSchedule') : url(':addSchedule') #}" name="page" id="page" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
            <div class="form-group">
                <a href="{# url(':manage_events') #}" >&laquo; Go Back to Events Details</a>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="{#  url(':add_event')  #}" ><i class="fa fa-plus"></i>&nbsp;Event</a>
            </div>
            {# @CSRF #}   
            <input type="hidden" name="eventId" value="{# $eventId #}"/>
            @if $formType <> 'edit':
                <div class="form-group">
                    <label for="ename" class="col-md-12">Event Name</label>
                    <div class="col-md-12">
                        <input id="ename" type="text" value="{# $eventName #}" class="form-control form-control-line" disabled/>
                    </div>
                </div>
                @else:
                    <input type="hidden" name="timeId" value="{# !empty($reqValues) ? $reqValues['timeId']: '' #}"/>
            @endif
            <wv-comp.admin-event-time />
            <div class="form-group">
                <div class="col-md-12">
                    <input type="submit" name="save" id="save" value="{# $formType === 'edit' ? 'Edit Schedule' : 'Save Schedule' #}" class="btn btn-success">
                </div>
            </div>
        </form>
    </wv-comp.admin-white-box>
</wv-admin-main>