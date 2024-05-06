
<div class="modal-content" id="certificationDisplay">
    <div class="modal-header">
        <h4 class="modal-title"></h4>
        <span class="fa fa-times" data-dismiss="modal"></span>
    </div>
    <div class="modal-body">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="container-fluid" style="width: 95%">
                            <iframe id="documentPreview" src="{{url($src)}}#zoom=80" style="width:100%;height:650px"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>