
<div class="modal-content">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> Add</h4>
    </div>
    <form method="POST" id="ptAdd">
        <div class="modal-body table-responsive">
            <div class="card card-info card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Employee</label>
                            <div id="employeeDiv">
                                <select class="form-control select2-default" name="employee">
                                    <option value="">Please select...</option>
                                    @foreach($employees as $row)
                                        <option value="{{$row->id}}">
                                            {{$row->id_no}} - {{$row->lastname}}, {{$row->firstname}} {{$row->extname}} @if(strlen($row->middlename) > 0){{substr($row->middlename, 0, 1) }}.@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Type</label>
                            <div id="typeDiv">
                                <select class="form-control select2-default" name="type">
                                    <option value="">Please select...</option>
                                    @foreach($pt_options as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 hide" id="nstpDiv">
                            <label>NSTP</label>
                            <select class="form-control select2-default" name="nstp">
                                <option value="">Please select...</option>
                                @foreach($nstp_options as $row)
                                    <option value="{{$row->id}}">{{$row->shorten}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label>Fund Source</label>
                            <div id="fundsourceDiv">
                                <select class="form-control select2-default" name="fund_source">
                                    <option value="">Please select...</option>
                                    @foreach($fund_sources as $row)
                                        <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Department</label>
                            <div id="departmentDiv">
                                <select class="form-control select2-default" name="department">
                                    <option value="">Please select...</option>
                                    @foreach($departments as $row)
                                        <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Rate</label>
                            <input type="number" class="form-control" name="rate" value="0">
                        </div>
                        <div class="col-md-12">
                            <label>Units</label>
                            <input type="number" class="form-control" name="units" value="0">
                        </div>
                        <div class="col-md-12">
                            <label>Total Hours</label>
                            <input type="number" class="form-control" name="total_hours" value="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
            <button type="button" class="btn btn-success btn-success-scan" name="submit">
                <span class="fa fa-check"></span> Submit</button>
        </div>
    </form>
</div>
<!-- /.modal-content -->
