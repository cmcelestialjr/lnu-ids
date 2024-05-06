
<div class="modal-content">
    <div class="modal-header">
        <h4><span class="fa fa-trash"></span> Delete Guideline</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-danger card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 center">
                        Are you really sure you want to delete this Guideline??
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-success btn-success-scan" id="btnDeleteGuideline" data-id="{{$query->id}}"><span class="fa fa-save"></span> Yes</button>
    </div>
</div>
<!-- /.modal-content -->
