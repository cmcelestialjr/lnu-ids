<div class="modal-content" id="roomsEditModal">
    <div class="modal-header">
        <h4 class="modal-title">
            <span class="fa fa-edit"></span> {{$room->shorten}} - {{$room->name}}
        </h4>
    </div>
    <div class="modal-body">
        <div class="card card-primary card-outline">
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="col-lg-12">
                        <input type="hidden" name="id" value="{{$room->id}}">
                        <label>Building</label>
                        <select class="form-control select2-default" name="building">
                            @foreach($buildings as $row)
                                @if($room->building_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->shorten}} - {{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                                @endif
                            @endforeach
                        </select>
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" value="{{$room->name}}">
                        <label>Shorten</label>
                        <input type="text" class="form-control" name="shorten" value="{{$room->shorten}}">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remarks">{{$room->remarks}}</textarea>
                        <label>Status</label>
                        <select class="form-control select2-default" name="status">
                            @foreach($statuses as $row)
                                @if($room->status_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif                                
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
