
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title"> <span class="fa fa-lock"></span></h4>
        <span class="fa fa-times btn-no-design" data-dismiss="modal">x</span>
    </div>
    <div class="modal-body">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12"><br>
                        <label>To proceed in updating your information please input your password below:</label>
                        <input type="password" class="form-control" id="proceedPassword" autocomplete="current-password">
                        <div style="padding-top:5px">
                        <button type="button" class="btn btn-success btn-success-scan" id="proceedEdit" 
                            data-id="{{$id}}" style="width:100%"><span class="fa fa-check"></span> Proceed</button>
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
<!-- /.modal-content -->
