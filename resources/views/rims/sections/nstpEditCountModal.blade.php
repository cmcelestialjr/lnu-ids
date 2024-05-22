<div class="modal-content" id="nstpEditCountModal">
    <div class="modal-header">
        <h4 class="modal-title">
           {{$nstp->code}} - {{$nstp->section_code}}
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-info card-outline">
                    <div class="card-body table-responsive">
                        <label>Max. Students</label>
                        <input type="number" class="form-control" name="max_student" value="{{$nstp->max_student}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"
            data-id="{{$nstp->id}}"
            data-x="{{$x}}"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
