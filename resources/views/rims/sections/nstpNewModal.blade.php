<div class="modal-content" id="nstpNewModal">
    <div class="modal-header">
        <h4 class="modal-title">
           <span class="fa fa-plus-square"></span> New
        </h4>
    </div>
    <div class="modal-body">
        <div class="card card-info card-outline">
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="col-lg-12">
                        <label>NSTP</label>
                        <select class="form-control select2-default" name="nstp">
                            @foreach ($nstps as $row)
                               <option value="{{$row->id}}" data-n="{{$row->max_student}}">{{$row->shorten}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label>Max. Students</label>
                        <input type="number" class="form-control" name="max_student" value="100">
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
