
<div class="modal-content">
    <div class="modal-header">
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12" id="documentPreviewDiv">
                        <input type="hidden" id="type_print" value="{{$type_print}}">
                        <iframe id="documentPreview" src="" style="width:100%;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<script src="{{ asset('assets/js/hrims/payroll/view/printPdfSrc.js') }}"></script>
<!-- /.modal-content -->
