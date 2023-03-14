
<div class="modal-content" id="curriculumNewModal">
    <div class="modal-header">
        <h4 class="modal-title">
            New Curriculum
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-md-12">
                <label>Year</label>
                <div class="input-group date">
                    <div class="input-group-append">
                        <div class="input-group-text">From</div>
                    </div>
                    <input type="text" class="form-control yearpicker" name="year_from" value="{{date('Y')}}">
                </div>
            </div>
            <div class="col-md-12">
                <div class="input-group date">
                    <div class="input-group-append">
                        <div class="input-group-text">To&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    </div>
                    <input type="text" class="form-control yearpicker" name="year_to" value="{{date('Y')+1}}">
                </div>
            </div>
            <div class="col-md-12">
                <label>Remarks</label>
                <textarea class="form-control" name="remarks"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button type="button" class="btn btn-primary btn-primary-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
