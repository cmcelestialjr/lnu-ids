
<div class="modal-content">
    <div class="modal-header">
        <h4><span class="fa fa-trash"></span> {{$query->payroll_id}}</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 center">
                        <h4>Are you sure you want to delete this payroll of<br>
                        {{$query->etal}}<br>
                        {{$query->period}} ({{$query->name}})</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> No</button>
        <button type="button" class="btn btn-success"
            id="deletePayrollSubmit"
            data-id="{{$query->id}}">
            <span class="fa fa-check"></span> Yes</button>
    </div>
</div>
<!-- /.modal-content -->
