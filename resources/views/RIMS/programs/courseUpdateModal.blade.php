
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">
            
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-2">
                <label>Course Code</label>
                <input type="text" class="form-control req" name="code" value="{{$query->code}}">
            </div>
            <div class="col-md-8">
                <label>Descriptive Title</label>
                <input type="text" class="form-control req" name="name" value="{{$query->name}}">
            </div>
            <div class="col-md-2">
                <label>Units</label>
                <input type="number" class="form-control req" name="units" value="{{$query->units}}">
            </div>
            <div class="col-md-12">
                <br>
                <label>Pre-requisite</label>
                <div class="row">
                    <div class="col-lg-6">
                        <label>Name appear in Pre-requisite</label>
                        <input type="text" class="form-control" name="pre_name" value="{{$query->pre_name}}">
                    </div>
                    <div class="col-lg-4">
                    </div>
                    <div class="col-lg-2">
                        <br><br>
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="checkboxPrimary1" class="all">
                                <label for="checkboxPrimary1">
                                    Check All
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="courseTablePre">
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button type="button" class="btn btn-primary btn-primary-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
