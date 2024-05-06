<div class="modal-content" id="dtrInputDurationModal">
    <div class="modal-header">
        <h4 class="modal-title">
           {{$query->lastname}}, {{$query->firstname}} - {{$date_name}}
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-info card-outline">
                    <div class="card-body table-responsive">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>Time Type</label>
                                <select class="form-control select2-primary" name="time_type">
                                    @foreach($time_type_ as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>                                       
                                    @endforeach
                                </select>
                                <label>Day From</label>
                                <input type="number" class="form-control" name="day_from" value="1">
                                <label>Day To</label>
                                <input type="number" class="form-control" name="day_to" value="1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
