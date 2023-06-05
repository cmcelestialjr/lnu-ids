
<div class="modal-content {{$class}}" id="employeeViewModal">
    <div class="modal-header">
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-info">
                    <h4>Information of <b>
                        {{$name}}
                        &nbsp; &nbsp; <button class="btn btn-primary btn-primary-scan"
                                        id="employeeInformation"
                                        data-id="{{$query->id}}"
                                        ><span class="fa fa-edit"></span> More Information</button>
                    </b>
                    </h4>
                    <div style="float:right;"></div>
                </div>
            </div>
            <div class="col-md-12 table-responsive">
                <table class="table" id="viewStaffIndividualTable">
                    <tr>
                        <td style="width: 13%">Name:</td>
                        <td style="width: 24%"><b>
                            <span id="nameView">{{$name}}</span>
                        </b></td>
                        <td>Employee No.:</td>
                        <td><b>
                                {{$query->id_no}}</b></td>
                        <td rowspan="7" class="center" style="width: 26%">
                            <button class="btn-no-design">
                                @if($query->image==NULL)
                                    <img src="{{ asset('assets/images/icons/png/user.png') }}" id="staff_open_img_upload" class="profile-img">
                                @else
                                    <img src="{{ asset($query->image) }}" id="staff_open_img_upload" class="profile-img">
                                @endif
                                <img src="{{secure_asset('/assets/images/loader/giphy.gif')}}" id="staff_loader_img" class="profile-img hide">
                            </button>
                            @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                                <input type="file" id="staff_img_upload" accept="image/*" style="display:none"/>
                                <button class="btn btn-success btn-success-scan btn-sm hide" 
                                    id="staff_img_upload_submit"
                                    data-id="{{$query->id}}">
                                <span class="fa fa-upload"></span> Upload
                                </button><br><br>
                                <span id="staff_img_message"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Sex:</td>
                        <td><b><span id="sexView">
                            @if(isset($query->personal_info->sexs->name))
                                {{$query->personal_info->sexs->name}}
                            @endif
                            </span></b></td>
                        <td style="width: 13%">Position:</td>
                        <td style="width: 24%"><b>
                            @if(isset($query->employee_default))
                                {{$query->employee_default->position_title}} ({{$query->employee_default->position_shorten}})
                            @endif
                            </b>
                        </td>                        
                    </tr>
                    <tr>
                        <td>Civil Status:</td>
                        <td><b><span id="civilStatusView">
                            @if(isset($query->personal_info->civil_statuses->name))
                                {{$query->personal_info->civil_statuses->name}}
                            @endif
                            </span></b>
                        </td>
                        <td>Monthly Salary:</td>
                        <td><b>
                            @if(isset($query->employee_default->salary))
                                {{number_format($query->employee_default->salary,2)}}
                            @endif
                            </b>
                        </td>                        
                    </tr>
                    <tr>
                        <td>Birthdate:</td>
                        <td><b><span id="bdateView">
                            @if($query->personal_info->dob!=NULL)
                            {{date('F d, Y', strtotime($query->personal_info->dob))}}
                            @endif
                        </span></b></td>
                        <td>Employment Status:</td>
                        <td><b>
                            @if(isset($query->employee_default->emp_stat->name))
                                {{$query->employee_default->emp_stat->name}}
                            @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><b><span id="emailView">{{$query->personal_info->email}}</span></b></td>
                        <td>Email Official:</td>
                        <td><b><span id="emailView">{{$query->personal_info->email_official}}</span></b></td>
                        {{-- <td>Fund Source:</td>
                        <td><b>
                            @if(isset($query->staff_works->fund_sources->fund_shorten))
                                {{$query->staff_works->fund_sources->fund_shorten}}
                            @endif
                            </b>
                        </td> --}}
                    </tr>
                    <tr>
                        <td>Contact:</td>
                        <td><b><span id="contactView">{{$query->contact_no}}</span></b></td>
                        <td>Contact Official:</td>
                        <td><b><span id="contactView">{{$query->contact_no_official}}</span></b></td>
                    </tr>
                    <tr>
                        <td>Date of Entry:</td>
                        <td><b>{{date('F d, Y', strtotime($query->date_entry->date_from))}}</b></td>
                    </tr>
                    <tr>
                        <td>Status:</td>
                        <td><b>
                            @if($query->status_id==1)
                            <span id="employee_status" class="btn btn-success btn-success-scan" data-id="{{$query->id}}">Active</span>
                            @else
                            <span id="employee_status" class="btn btn-danger btn-danger-scan" data-id="{{$query->id}}">InActive
                                @if(isset($query->employee_default))
                                    @if($query->employee_default->date_separation!=NULL)
                                        <br>{{date('M d, Y',strtotime($query->employee_default->date_separation))}}
                                    @endif
                                @endif
                            </span>
                            @endif
                        </b></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-12">
                <div class="card card-primary card-outline" id="workDiv">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12"><br>
                                <button class="btn btn-info btn-info-scan" name="newModal" style="float:right"
                                    data-id="{{$query->id}}">
                                    <span class="fa fa-plus"></span> New
                                </button><br><br>
                                <table id="workTable" class="table table-bordered table-fixed"
                                    data-toggle="table"
                                    data-search="true"
                                    data-height="600"
                                    data-buttons-class="primary"
                                    data-show-export="true"
                                    data-show-columns-toggle-all="true"
                                    data-mobile-responsive="true"
                                    data-pagination="true"
                                    data-page-size="10"
                                    data-page-list="[10, 50, 100, All]"
                                    data-loading-template="loadingTemplate"
                                    data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                                    <thead>
                                        <tr>
                                            <th data-field="f1" data-sortable="true" data-align="center" rowspan="2">#</th>
                                            <th data-sortable="true" data-align="center" colspan="2">Period</th>
                                            <th data-field="f4" data-sortable="true" data-align="center" rowspan="2">Position</th>
                                            <th data-field="f5" data-sortable="true" data-align="center" rowspan="2">Designation</th>
                                            <th data-field="f6" data-sortable="true" data-align="center" rowspan="2">Salary</th>
                                            <th data-field="f7" data-sortable="true" data-align="center" rowspan="2">SG</th>
                                            <th data-field="f8" data-sortable="true" data-align="center" rowspan="2">Step</th>
                                            <th data-field="f9" data-sortable="true" data-align="center" rowspan="2">Type</th>
                                            <th data-field="f10" data-sortable="true" data-align="center" rowspan="2">Status of Appoint</th>
                                            <th data-field="f11" data-sortable="true" data-align="center" rowspan="2">Office Entity</th>
                                            <th data-field="f12" data-sortable="true" data-align="center" rowspan="2">LWOP</th>
                                            <th data-sortable="true" data-align="center" colspan="2">Separation</th>
                                            <th data-field="f15" data-sortable="true" data-align="center" rowspan="2">Remarks</th>
                                            <th data-field="f16" data-sortable="true" data-align="center" rowspan="2">Docs</th>
                                        </tr>
                                        <tr>
                                            <th data-field="f2" data-sortable="true" data-align="center">From</th>
                                            <th data-field="f3" data-sortable="true" data-align="center">To</th>
                                            <th data-field="f13" data-sortable="true" data-align="center">Date</th>
                                            <th data-field="f14" data-sortable="true" data-align="center">Cause</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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
