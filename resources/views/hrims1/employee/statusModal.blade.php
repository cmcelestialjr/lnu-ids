
<div class="modal-content" id="statusModal">
    <div class="modal-header">
        <h4><span class="fa fa-info"></span> {{$query->lastname}}, {{$query->firstname}}</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="id" value="{{$query->id}}">
                    <div class="col-lg-12">
                        <label>Status</label>
                        <select class="form-control select2-primary" name="status">
                            @foreach($status as $row)
                                @if($query->emp_status_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12" id="separationDetails">
                        <label>Separation Cause</label>
                        <input type="text" class="form-control" name="cause" value="{{$cause}}">
                        <label>Separation Date</label>
                        <input type="text" class="form-control datePicker" name="separation_date" value="{{$separation_date}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-plus"></span> Submit</button>
    </div>
</div>
<script src="{{ asset('assets/js/hrims/employee/employee_status.js') }}"></script>
<!-- /.modal-content -->
