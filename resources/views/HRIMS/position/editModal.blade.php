
<div class="modal-content" id="editModal">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> Edit Position</h4>
    </div>
    <div class="modal-body table-responsive">
        <span class="text-require">*</span> Required fields
        <div class="card card-primary card-outline">
            <div class="card-body">
                <input type="hidden" name="id" value="{{$query->id}}">
                <div class="row">
                    <div class="col-lg-4">
                        <label>Item No.<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="item_no" value="{{$query->item_no}}">
                    </div>
                    <div class="col-lg-4">
                        <label>Title<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{$query->name}}">
                    </div>
                    <div class="col-lg-4">
                        <label>Shorten<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="shorten" value="{{$query->shorten}}">
                    </div>
                    <div class="col-lg-12">
                        <br>
                    </div>
                    <div class="col-lg-4">
                        <label>Salary<span class="text-require">*</span></label>
                        <input type="number" class="form-control" name="salary" value="{{$query->salary}}">
                    </div>
                    <div class="col-lg-4">
                        <label>SG<span class="text-require">*</span></label>
                        <input type="number" class="form-control" name="sg" value="{{$query->sg}}">
                    </div>
                    <div class="col-lg-4">
                        <label>Level<span class="text-require">*</span></label>
                        <input type="number" class="form-control" name="level" value="{{$query->level}}">
                    </div>
                    <div class="col-lg-12">
                        <br>
                    </div>
                    <div class="col-lg-4">
                        <label>Date of Creation<span class="text-require">*</span></label>
                        <input type="text" class="form-control datePicker" name="date_created" value="{{date('m-d-Y',strtotime($query->date_created))}}">
                    </div>
                    <div class="col-lg-4">
                        <label>Designation</label>
                        <div id="designationList">
                            <select class="form-control select2-default designationList" name="designation">
                                <option value="none">None</option>
                                @if($query->designation_id!=NULL)
                                <option value="{{$query->designation_id}}" selected>{{$query->designation->name}}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label>Employment Status<span class="text-require">*</span></label>
                        <select class="form-control select2-default" name="emp_stat">
                            @foreach($emp_stat as $row)
                                @if($query->emp_stat_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif                                
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
                                @if($query->fund_source_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->code}} - {{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->code}} - {{$row->name}}</option>
                                @endif 
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Fund Services<span class="text-require">*</span></label>
                        <select class="form-control select2-default" name="fund_services">
                            @foreach($fund_services as $row)
                                @if($query->fund_services_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->shorten}} - {{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                                @endif 
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Role<span class="text-require">*</span></label>
                        <select class="form-control select2-default" name="role">
                            @foreach($role as $row)
                                @if($query->role_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Status<span class="text-require">*</span></label>
                        <select class="form-control select2-default" name="status">
                            @foreach($status as $row)
                                @if($query->status_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif
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
                            @if($query->gov_service=='Y')
                                <option value="Y" selected>Yes</option>
                                <option value="N">No</option>
                            @else
                                <option value="Y">Yes</option>
                                <option value="N" selected>No</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Remarks</label>
                        <textarea name="remarks" style="width: 100%">{{$query->remarks}}</textarea>
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
