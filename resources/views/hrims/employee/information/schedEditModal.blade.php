
<div class="modal-content" id="schedEditModal">
    <div class="modal-header">

    </div>
    <div class="modal-body">
        <input type="hidden" name="time_id" value="{{$query->id}}">
        <div class="row">
            <div class="col-lg-4">
                <label>Option</label>
                <select class="form-control select2-info" name="option">
                    @foreach($sched_option as $row)
                        @if($query->option_id==$row->id)
                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                        @else
                            <option value="{{$row->id}}">{{$row->name}}</option>
                        @endif                        
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <label>Duration</label>
                <input type="text" class="form-control dateRange" name="duration" value="{{date('m/d/Y',strtotime($query->date_from))}} - {{date('m/d/Y',strtotime($query->date_to))}}">
            </div>
            <div class="col-lg-4">
                <label>Rotation Duty?</label> {{$query->is_rotation_duty}}
                <select class="form-control select2-info" name="is_rotation_duty">
                    @if($query->is_rotation_duty=='Yes')
                        <option value="Yes" selected>Yes</option>
                        <option value="No">No</option>
                    @else
                        <option value="Yes">Yes</option>
                        <option value="No" selected>No</option>
                    @endif
                </select>
            </div>
            <div class="col-lg-6">
                <label>Time From</label>
                <input type="time" class="form-control time_input" name="time_from" value="{{$query->time_from}}">
            </div>
            <div class="col-lg-6">
                <label>Time To</label>
                <input type="time" class="form-control time_input" name="time_to" value="{{$query->time_to}}">
            </div>
            <div class="col-lg-12 table-responsive center" id="daysList"><br>
                
            </div>
            <div class="col-lg-12 center"><br>
                <label>Remarks</label>
                <textarea name="remarks" style="width: 100%">{{$query->remarks}}</textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-save"></span> Save</button>
    </div>
</div>
<!-- /.modal-content -->
