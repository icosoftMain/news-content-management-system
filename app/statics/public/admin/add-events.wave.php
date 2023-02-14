<wv-admin-main crumpTitle={Event Form} {crumpLink} crumpSlashTitle={/&nbsp;Event Form}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <form action="{# $formType === 'edit' ? url(':updateEvent') : url(':setEvent') #}" name="page" id="page" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
            <div class="form-group">
                <a href="{# url(':manage_events') #}" >&laquo; Go Back to Events Details </a>
                @if $formType === 'edit':
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{#  url(':add_event')  #}" ><i class="fa fa-plus"></i>&nbsp;Event</a>
                @endif
            </div>
            {# @CSRF #}
            <div class="form-group">
                <label for="eventName" class="col-md-12">Event Name</label>
                <div class="col-md-12">
                    <input type="text" name="eventName" id="eventName" value="{# !empty($reqValues) ? $reqValues['eventName']: '' #}" class="form-control form-control-line" placeholder="Event Name" />
                    <br />
                </div>
            </div>
            <input type="hidden" name="eventId" value="{# $eventId #}"/>
            <div class="form-group">
                <label for="eventLocation" class="col-md-12">Event Location</label>
                <div class="col-md-12">
                    <input type="text" name="_location" id="eventLocation" value="{# !empty($reqValues) ? $reqValues['_location']: '' #}" class="form-control form-control-line" placeholder="Event Location" />
                    <br />
                </div>
            </div>
            <div class="form-group">
                <label for="eventDescription" class="col-md-12">Event Description</label>
                <div class="col-md-12">
                    <textarea name="_description" id="eventDescription" rows ="20" cols="50" class="form-control form-control-line ckeditor" placeholder="Type Event Description">
                        {# !empty($reqValues) ? $reqValues['_description']: '' #}
                    </textarea>
                    <br />
                </div>
            </div>
            @if $formType === 'add':
                <wv-comp.admin-event-time />
            @endif
            <div class="form-group">
                <label for="event_target" class="col-md-12">Event Type</label>
                <div class="col-md-12">
                    <select name="eventType" id="event_target" class="form-control form-control-line">
                        @each $eventTypes as $type:
                            <option value="{# $type->id #}">{# $type->name #}</option>
                        @endeach
                    </select>
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label for="uploadimage" class="col-md-12">Upload Event Poster</label>
                <div class="col-md-12">
                    <input type="file" name="eventImage" id="uploadimage" class="form-control form-control-line">
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label for="Do you want to publish on event page ?" class="col-md-12">Do you want to publish on event page ?</label>
                <div class="col-md-12">
                    <span style="display:inline; padding:0.6em" class="radio-primary">
                        <label>
                            <input type="radio" name="published" value="Y" {# !empty($reqValues) && $reqValues['published'] === 'Y' ? 'checked': '' #}> Yes
                        </label>
                    </span>
                    <span style="display:inline; padding:0.6em" class="radio-primary">
                        <label>
                            <input type="radio" name="published" value="N" {# !empty($reqValues) && $reqValues['published'] === 'N' ? 'checked': '' #}> No
                        </label>
                    </span>
                    <br>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="submit" name="save" id="save" value="{# $formType === 'edit' ? 'Edit Event' : 'Save Event' #}" class="btn btn-success">
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="hidden" name="createdBy" id="createdby" value="{# $adminId #}">
                    </div>
                </div>
            </div>
        </form>
    </wv-comp.admin-white-box>
</wv-admin-main>