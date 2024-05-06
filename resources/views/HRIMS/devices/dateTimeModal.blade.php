
<div class="modal-content" id="dateTimeModalSubmit">
    <div class="modal-header">
        <h4><span class="fa fa-calendar"></span> Device DateTime</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <label>{{$device->name}} - {{$device->ipaddress}}</label><br>
                <label>Date</label>
                <input type="text" class="form-control datePicker" name="date" value="{{date('m-d-Y',strtotime($device->dateTime))}}">
                <label>Time</label>
                <input type="time" class="form-control" name="time" value="{{date('H:i',strtotime($device->dateTime))}}">
                <input type="hidden" name="id" value="{{$id}}">
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button class="btn btn-success btn-success-scan" name="submit">
            <span class="fa fa-save"></span> Submit
        </button>
    </div>
</div>
<!-- /.modal-content -->
