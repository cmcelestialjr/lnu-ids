
<div class="modal-content" id="removeEmployeeModal">
    <div class="modal-header text-require">
        <h4><span class="fa fa-times"></span> Remove</h4>
        <input type="hidden" name="employee_id" value="{{$query->id}}">
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-danger card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 center">
                        <h4>Are you really sure you want to remove <br>
                            {{$query->employee->lastname}}, {{$query->employee->firstname}} {{$query->employee->extname}} {{$query->employee->middlename}}
                            <br>in this payroll??
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> No</button>  
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Yes</button>      
    </div>
</div>
<!-- /.modal-content -->
