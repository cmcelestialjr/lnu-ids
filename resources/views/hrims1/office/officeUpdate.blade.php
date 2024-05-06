
<div class="modal-content" id="officeUpdate">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> Update Office</h4>
    </div>
    <div class="modal-body table-responsive">
        <span class="text-require">*</span> Required field
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Name<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{$office->name}}">
                    </div>
                    <div class="col-lg-12">
                        <label>Shorten<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="shorten" value="{{$office->shorten}}">
                    </div>
                    <div class="col-lg-12">
                        <label>Office Type</label>
                        <select class="form-control select2-default" name="office_type">
                            @foreach($office_type as $row)
                                @if($row->id==$office->office_type_id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label>Parent Office</label>
                        <select class="form-control select2-default" name="parent_office">
                            <option value="0">None</option>
                            @foreach($parent_office as $row)
                                @if($row->id==$office->parent_office_id)
                                    <option value="{{$row->id}}" selected>{{$row->name}} - {{$row->shorten}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}} - {{$row->shorten}}</option>
                                @endif                                
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit" data-id="{{$office->id}}"><span class="fa fa-plus"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
