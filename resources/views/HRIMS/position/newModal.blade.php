
<div class="modal-content" id="newModal">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> New Position</h4>
    </div>
    <div class="modal-body table-responsive">
        <span class="text-require">*</span> Required fields
        <div class="card card-primary card-outline">
            <div class="card-body">
                <input type="hidden" name="id" value="">
                <div class="row">
                    <div class="col-lg-4">
                        <label>Item No.<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="item_no">
                    </div>
                    <div class="col-lg-4">
                        <label>Title<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="col-lg-4">
                        <label>Shorten<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="shorten">
                    </div>
                    <div class="col-lg-12">
                        <br>
                    </div>
                    <div class="col-lg-4">
                        <label>Salary<span class="text-require">*</span></label>
                        <input type="number" class="form-control" name="salary">
                    </div>
                    <div class="col-lg-4">
                        <label>SG<span class="text-require">*</span></label>
                        <input type="number" class="form-control" name="sg">
                    </div>
                    <div class="col-lg-4">
                        <label>Level<span class="text-require">*</span></label>
                        <input type="number" class="form-control" name="level">
                    </div>
                    <div class="col-lg-12">
                        <br>
                    </div>
                    <div class="col-lg-4">
                        <label>Date of Creation<span class="text-require">*</span></label>
                        <input type="text" class="form-control datePicker" name="date_created">
                    </div>
                    <div class="col-lg-4">
                        <label>Designation</label>
                        <div id="designationList">
                            <select class="form-control select2-default designationList" name="designation">
                                <option value="none">None</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label>Employment Status<span class="text-require">*</span></label>
                        <select class="form-control select2-default" name="emp_stat">
                            @foreach($emp_stat as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <br>
                    </div>
                    <div class="col-lg-4">
                        <label>Fund Source<span class="text-require">*</span></label>
                        <select class="form-control select2-default" name="fund_source">
                            @foreach($fund_source as $row)
                                <option value="{{$row->id}}">{{$row->code}} - {{$row->name}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Role<span class="text-require">*</span></label>
                        <select class="form-control select2-default" name="role">
                            @foreach($role as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Status<span class="text-require">*</span></label>
                        <select class="form-control select2-default" name="status">
                            @foreach($status as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Schedule<span class="text-require">*</span></label>
                        <select class="form-control select2-default" name="schedule">
                            @foreach($sched as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Gov't Service?<span class="text-require">*</span></label>
                        <select class="form-control select2-default" name="gov_service">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Remarks</label>
                        <textarea name="remarks" style="width: 100%"></textarea>
                    </div>
                    <div class="col-lg-12">
                        <br><br>
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
<script src="{{ asset('assets/js/hrims/position/position_designation.js') }}"></script>
<!-- /.modal-content -->
