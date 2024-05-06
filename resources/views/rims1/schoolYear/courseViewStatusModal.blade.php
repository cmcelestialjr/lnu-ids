
<div class="modal-content" id="courseViewStatusModal">
    <div class="modal-header">
        <h4 class="modal-title">{{$course->course->code}} - {{$course->course->name}}</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-lg-12">
                <label>Status</label>
                <select class="form-control select2-info" name="status">
                    @foreach($statuses as $row)
                        @if($course->status_id==$row->id)
                        <option value="{{$row->id}}" selected>{{$row->name}}</option>
                        @else
                        <option value="{{$row->id}}">{{$row->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>   
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
