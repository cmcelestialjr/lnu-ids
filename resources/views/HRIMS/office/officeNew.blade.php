
<div class="modal-content" id="officeNew">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> New Office</h4>
    </div>
    <div class="modal-body table-responsive">
        <span class="text-require">*</span> Required field
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Name<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="col-lg-12">
                        <label>Shorten<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="shorten">
                    </div>
                    <div class="col-lg-12">
                        <label>Office Type</label>
                        <select class="form-control select2-default" name="office_type">
                            @foreach($office_type as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label>Parent Office</label>
                        <select class="form-control select2-default" name="parent_office">
                            <option value="0">None</option>
                            @foreach($parent_office as $row)
                                <option value="{{$row->id}}">{{$row->name}} - {{$row->shorten}}</option>
                            @endforeach
                        </select>
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
