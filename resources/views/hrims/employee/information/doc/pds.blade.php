
<div class="modal-content" id="pds-modal">
    <div class="modal-header">
        <span class="fa fa fa-address-card"> PDS</span>
    </div>
    <div class="modal-body card card-primary card-outline">
        <div class="row">
            <div class="col-lg-3">
                <select class="form-control select2-info" id="year">
                    @for($i=date('Y');$i>=2000;$i--)
                        <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </div>
            <div class="col-lg-9">
                <button class="btn btn-primary btn-primary-scan float-right">
                    <span class="fa fa-upload"></span> Upload PDS
                </button>
                <button class="btn btn-info btn-info-scan float-right">
                    <span class="fa fa-print"></span> Generate PDS
                </button>
            </div>
            <div class="col-lg-12" id="pds-div">
                <iframe id="documentPreview" src="{{url($doc)}}" style="height:900px;width:100%;"></iframe>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<!-- /.modal-content -->
