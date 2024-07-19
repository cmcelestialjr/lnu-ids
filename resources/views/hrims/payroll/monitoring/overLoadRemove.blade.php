
<div class="modal-content">
    <div class="modal-header">
        <h4><span class="fa fa-trash"></span> Remove</h4>
    </div>
    <form method="POST" id="olRemove">
        <div class="modal-body table-responsive">
            <div class="card card-danger card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            Are you sure want to remove
                                <br><label>{{$name}} {{$pt_option}}</label><br>
                                W/<br>
                                Rate: <label>{{$rate}}</label><br>
                                Units: <label>{{$units}}</label><br>
                                Total Hours: <label>{{$total_hours}}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <input type="hidden" name="id" value="{{$id}}">
            <input type="hidden" name="w" value="{{$work_id}}">
            <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> No</button>
            <button type="button" class="btn btn-success btn-success-scan" name="submit">
                <span class="fa fa-check"></span> Yes</button>
        </div>
    </form>
</div>
<!-- /.modal-content -->
