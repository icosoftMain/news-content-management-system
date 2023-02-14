<div class="form-group">
    <label for="start_date" class="col-md-12">Event Start Date</label>
    <div class="col-md-12">
        <input type="date" name="startDate" id="start_date" value="{# !empty($reqValues) ? $reqValues['startDate']: '' #}" data-date-format="dd-mm-yyyy" data-provide="datepicker-inline" class="datepicker form-control form-control-line" placeholder="Event Start Date">
        <br>
    </div>
</div>
<div class="form-group">
    <label for="end_date" class="col-md-12">Event End Date</label>
    <div class="col-md-12">
        <input type="date" name="endDate" id="end_date" value="{# !empty($reqValues) ? $reqValues['endDate']: '' #}" data-date-format="dd-mm-yyyy" data-provide="datepicker" class="datepicker form-control form-control-line" placeholder="Event End Date ">
        <br>
    </div>
</div>
<div class="form-group">
    <label for="start_time" class="col-md-12">Event Start Time</label>
    <div class="col-md-12">
        <input type="time" name="startTime" id="start_time" value="{# !empty($reqValues) ? $reqValues['startTime']: '' #}" data-date-format="dd-mm-yyyy" data-provide="datepicker-inline" class="datepicker form-control form-control-line" placeholder="Event Start Date">
        <br>
    </div>
</div>
<div class="form-group">
    <label for="end_time" class="col-md-12">Event End Time</label>
    <div class="col-md-12">
        <input type="time" name="endTime" id="end_time" value="{# !empty($reqValues) ? $reqValues['endTime']: '' #}" data-date-format="dd-mm-yyyy" data-provide="datepicker" class="datepicker form-control form-control-line" placeholder="Event End Date ">
        <br>
    </div>
</div>