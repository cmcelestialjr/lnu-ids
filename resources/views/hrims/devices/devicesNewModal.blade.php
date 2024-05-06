
<div class="modal-content" id="devicesNewModalForm">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> New Device</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <label>Device Name</label>
                <input type="text" class="form-control" name="name">
                <label>Ip Address</label>
                <input type="text" class="form-control" name="ipaddress">
                <label>Port</label>
                <input type="number" class="form-control" name="port">
                <label>Remarks</label>
                <textarea class="form-control" name="remarks"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button class="btn btn-success btn-success-scan" name="submit">
            <span class="fa fa-save"></span> Submit
        </button>
    </div>
</div>
<!-- /.modal-content -->
