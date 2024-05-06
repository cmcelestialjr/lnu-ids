<div class="modal-content" id="roomsNewModal">
    <div class="modal-header">
        <h4 class="modal-title">
           New Room
        </h4>
    </div>
    <div class="modal-body">
        <div class="card card-primary card-outline">
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Building</label>
                        <select class="form-control select2-default" name="building">
                            @foreach($buildings as $row)
                                <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                            @endforeach
                        </select>
                        <label>Name</label>
                        <input type="text" class="form-control" name="name">
                        <label>Shorten</label>
                        <input type="text" class="form-control" name="shorten">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remarks"></textarea>
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
