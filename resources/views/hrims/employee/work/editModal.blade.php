
<div class="modal-content" id="workEditModal">
    <input type="hidden" name="id" value="{{$query->id}}">
    <div class="modal-header">
        {{$query->user->lastname}}, {{$query->user->firstname}}
    </div>
    <div class="modal-body">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <span class="text-require">*</span> Required fields
                <div class="row">
                    <div class="col-lg-3">
                        <label>Date From<span class="text-require">*</span></label>
                        <input type="text" class="form-control datePicker" name="date_from" value="{{date('m-d-Y',strtotime($query->date_from))}}">
                    </div>
                    <div class="col-lg-3">
                        <label>Date To<span class="text-require">*</span></label>
                        @if($query->date_to=='present')
                            <label><input type="radio" name="date_to_option" value="present" checked> present</label>
                            <label><input type="radio" name="date_to_option" value="date"> Date</label>
                            <input type="text" class="form-control datePicker" name="date_to" readonly>
                        @else
                            <label><input type="radio" name="date_to_option" value="present"> present</label>
                            <label><input type="radio" name="date_to_option" value="date" checked> Date</label>
                            <input type="text" class="form-control datePicker" name="date_to" value="{{date('m-d-Y',strtotime($query->date_to))}}">
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-6">
                                <label>Position<span class="text-require">*</span></label>
                                @if($query->position_id==NULL)
                                    <label><input type="radio" name="position_option" value="List"> List</label>
                                    <label><input type="radio" name="position_option" value="None" checked> None</label>
                                    <div class="hide" id="positionList">
                                        <select class="form-control select2-primary positionList" name="position_id">
                                        </select>
                                    </div>
                                    <input type="text" class="form-control" name="position_title" placeholder="Position Title" value="{{$query->position_title}}">
                                @else
                                    <label><input type="radio" name="position_option" value="List" checked> List</label>
                                    <label><input type="radio" name="position_option" value="None"> None</label>
                                    <div id="positionList">
                                        <select class="form-control select2-primary positionList" name="position_id">
                                            <option value="{{$query->position_id}}">{{$query->position_title}}</option>
                                        </select>
                                    </div>
                                    <input type="text" class="form-control hide" name="position_title" placeholder="Position Title" value="{{$query->position_title}}">
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <label>Shorten<span class="text-require">*</span></label>
                                @if($query->position_id==NULL)
                                    <input type="text" class="form-control" name="position_shorten" value="{{$query->position_shorten}}">
                                @else
                                    <input type="text" class="form-control" name="position_shorten" readonly value="{{$query->position_shorten}}">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12"><br>
                    </div>
                    <div class="col-lg-3">
                        <label>Salary<span class="text-require">*</span></label>
                        <input type="number" class="form-control" name="salary" value="{{$query->salary}}">
                    </div>
                    <div class="col-lg-3">
                        <label>SG<span class="text-require">*</span></label>
                        <input type="number" class="form-control" name="sg" value="{{$query->sg}}">
                    </div>
                    <div class="col-lg-3">
                        <label>Step<span class="text-require">*</span></label>
                        <input type="number" class="form-control" name="step" value="{{$query->step}}">
                    </div>
                    <div class="col-lg-3">
                        <label>Employment Status<span class="text-require">*</span></label>
                        @php
                            if($query->position_id==NULL){
                                $disabled = '';
                            }else{
                                $disabled = 'disabled';
                            }
                        @endphp
                        <select class="form-control select2-primary" name="emp_stat" {{$disabled}}>
                            @foreach($emp_stat as $row)
                                @if($query->emp_stat_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12"><br>
                    </div>
                    <div class="col-lg-3">
                        <label>Gov't?<span class="text-require">*</span></label>
                        <select class="form-control select2-primary" name="gov_service" {{$disabled}}>
                            @if($query->gov_service=='Y')
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            @else
                                <option value="Y">Yes</option>
                                <option value="N" selected>No</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Fund Source<span class="text-require">*</span></label>
                        <select class="form-control select2-primary" name="fund_source" {{$disabled}}>
                            <option value="none">None</option>
                            @foreach($fund_source as $row)
                                @if($query->fund_source_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->code}} ({{$row->name}})</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->code}} ({{$row->name}})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Fund Services<span class="text-require">*</span></label>
                        <select class="form-control select2-primary" name="fund_services" {{$disabled}}>
                            <option value="none">None</option>
                            @foreach($fund_services as $row)
                                @if($query->fund_services_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->shorten}} ({{$row->name}})</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->shorten}} ({{$row->name}})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Designation</label>
                        <div id="designationList">
                            <select class="form-control select2-primary designationList" name="designation">
                                <option value="none">None</option>
                                @if($query->designation_id!=NULL)
                                    <option value="{{$query->designation_id}}" selected>{{$query->designation->name}}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12"><br>
                    </div>
                    <div class="col-lg-3">
                        @php
                        if($query->designation_id==NULL){
                            $disabled1 = 'disabled';
                        }else{
                            $disabled1 = '';
                        }
                        @endphp
                        <label>OIC?<span class="text-require">*</span></label>
                        <input type="radio" name="oic" id="oic_yes" value="Yes" {{$disabled1}}> <label for="oic_yes">Yes</label> &nbsp;
                        <input type="radio" name="oic" id="oic_no" value="No" checked {{$disabled1}}> <label for="oic_no">No</label>
                    </div>
                    <div class="col-lg-3">
                        <label>Designation Type<span class="text-require">*</span></label>
                        <select class="form-control select2-primary" name="credit_type" {{$disabled1}}>
                            <option value="none">None</option>
                            @foreach($credit_type as $row)
                                @if($query->credit_type_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Option<span class="text-require">*</span></label>
                        <select class="form-control select2-primary" name="role" {{$disabled}}>
                            @foreach($user_role as $row)
                                @if($query->role_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Agency<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="office" value="{{$query->office}}">
                    </div>

                    <div class="col-lg-12"><br>
                    </div>
                    <div class="col-lg-3">
                        <label for="office">Office</label>
                        <select class="form-control select2-primary" name="office_id" id="office">
                            <option value="0">None</option>
                            @foreach($offices as $row)
                                @if($query->office_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->shorten}} - {{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Separation Cause</label>
                        <input type="text" class="form-control" name="cause" value="{{$query->cause}}">
                    </div>
                    <div class="col-lg-3">
                        <label>Separation Date</label>
                        <input type="text" class="form-control" name="separation" value="{{$query->separation}}">
                    </div>
                    <div class="col-lg-3">
                        <label>Type<span class="text-require">*</span></label>
                        <select class="form-control select2-primary" name="type">
                            @foreach($work_type as $row)
                                @if($query->type_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12"><br>
                    </div>
                    <div class="col-lg-4">
                        <label>LWOP</label>
                        <textarea name="lwop" style="width: 100%">{{$query->lwop}}</textarea>
                    </div>
                    <div class="col-lg-4">
                        <label>Remarks</label>
                        <textarea name="remarks" style="width: 100%">{{$query->remarks}}</textarea>
                    </div>
                    <div class="col-lg-12"><br><br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-save"></span> Save</button>
    </div>
</div>
<script src="{{ asset('assets/js/hrims/employee/work/work.js') }}"></script>

<!-- /.modal-content -->
