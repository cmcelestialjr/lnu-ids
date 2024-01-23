
<div class="modal-content" id="groupNewModal">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> New Group</h4>
    </div>
    <div class="modal-body table-responsive">
        <span class="text-require">*</span> Required field
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Name<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="name">
                        <label>Employment Status<span class="text-require">*</span></label>
                        <div id="emp_stat_select">
                            <select class="form-control select2-default" name="emp_stat[]" multiple>
                                @foreach($emp_stat as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label>Payroll Type<span class="text-require">*</span></label>
                        <div id="payroll_type_select">
                            <select class="form-control select2-default" name="payroll_type[]" multiple>
                                @foreach($payroll_type as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-plus"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
