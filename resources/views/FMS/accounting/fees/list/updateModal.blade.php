
<div class="modal-content" id="listUpdateModal">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> New Fee</h4>
    </div>
    <div class="modal-body table-responsive">
        <span class="text-require">*</span> Required fields
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Type</label>
                        <select class="form-control select2-default" name="type">
                            @foreach($type as $row)
                                @if($query->type_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif
                            @endforeach
                        </select>
                        <label>Name<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{$query->name}}">
                        <input type="hidden" name="id" value="{{$query->id}}">
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
<!-- /.modal-content -->
