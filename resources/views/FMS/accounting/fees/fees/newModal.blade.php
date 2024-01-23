
<div class="modal-content" id="feesNewModal">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> Add Fee</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <select class="form-control select2-default" name="fees" id="feesNewModalSelect">
                            @foreach($fees as $row)
                                <option value="{{$row->id}}">{{$row->name}}-{{$row->type->name}}</option>
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
