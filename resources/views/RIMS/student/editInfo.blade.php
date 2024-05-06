<div class="modal-content" id="editInfo">
    <div class="modal-header">
        <h4 class="modal-title"></h4>
        <span class="fa fa-times" data-dismiss="modal"></span>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="col-lg-12 col-sm-12">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs"
                                role="tablist"
                                style="font-size: 12px;">
                              <li class="nav-item">
                                <a class="nav-link @if($val=='Info') active @endif"
                                    data-toggle="pill"
                                    href="#edit-info"
                                    role="tab"
                                    aria-selected="true">Personal Info</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link @if($val=='Contact') active @endif"
                                    data-toggle="pill"
                                    href="#edit-contact"
                                    role="tab"
                                    aria-selected="true">Contact & Address</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link @if($val=='Educ') active @endif"
                                    data-toggle="pill"
                                    href="#edit-educ"
                                    role="tab"
                                    aria-selected="true">Educational Background</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link @if($val=='Fam') active @endif"
                                    data-toggle="pill"
                                    href="#edit-fam"
                                    role="tab"
                                    aria-selected="true">Family Background</a>
                              </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                              <div class="tab-pane fade fam-tab @if($val=='Info') show active @endif"
                                    id="edit-info"
                                    data-val="Info"
                                    role="tabpanel"
                                    style="min-height: 250px;">
                                <div class="row">
                                    <div class="col-lg-12 table-responsive">
                                        <table class="table">
                                            <tr>
                                                <td style="width: 15%"><label>Lastname:</label></td>
                                                <td style="width: 35%"><input type="text"
                                                        class="form-control"
                                                        name="lastname"
                                                        value="{{$query->lastname}}">
                                                </td>
                                                <td style="width: 15%"><label>Firstname:</label></td>
                                                <td style="width: 35%"><input type="text"
                                                        class="form-control"
                                                        name="firstname"
                                                        value="{{$query->firstname}}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>Middlename:</label></td>
                                                <td><input type="text"
                                                        class="form-control"
                                                        name="middlename"
                                                        value="{{$query->middlename}}">
                                                </td>
                                                <td><label>Extname:</label></td>
                                                <td><input type="text"
                                                        class="form-control"
                                                        name="extname"
                                                        value="{{$query->extname}}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>Nickname:</label></td>
                                                <td><input type="text"
                                                        class="form-control"
                                                        name="nickname"
                                                        value="{{$query->personal_info->nickname}}">
                                                </td>
                                                <td><label>Sex:</label></td>
                                                <td><select class="form-control select2-info"
                                                        name="sex"
                                                        style="width: 100%">
                                                        @php
                                                            $sex_id = 2;
                                                            if($query->personal_info->sex!=NULL){
                                                                $sex_id = $query->personal_info->sex;
                                                            }
                                                        @endphp
                                                        @foreach($sexs as $row)
                                                            @if($sex_id==$row->id)
                                                                <option value="{{$row->id}}"selected>{{$row->name}}</option>
                                                            @else
                                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>Civil Status:</label></td>
                                                <td><select class="form-control select2-info"
                                                        name="civil_status"
                                                        style="width: 100%">
                                                        @php
                                                            $civil_status_id = 2;
                                                            if($query->personal_info->civil_status_id!=NULL){
                                                                $civil_status_id = $query->personal_info->civil_status_id;
                                                            }
                                                        @endphp
                                                        @foreach($civil_statuses as $row)
                                                            @if($civil_status_id==$row->id)
                                                                <option value="{{$row->id}}"selected>{{$row->name}}</option>
                                                            @else
                                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><label>Birthdate:</label></td>
                                                <td>
                                                    <input type="text"
                                                        class="form-control datePicker"
                                                        name="dob"
                                                        value="@if(strtotime($query->personal_info->dob) !== false) {{date('m/d/Y',strtotime($query->personal_info->dob))}} @endif">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>Birthplace:</label></td>
                                                <td>
                                                    <input type="text"
                                                        class="form-control"
                                                        name="birthplace"
                                                        value="{{$query->personal_info->birthplace}}">
                                                </td>
                                                <td><label>Born Country:</label></td>
                                                <td><select class="form-control select2-info"
                                                        name="country"
                                                        style="width: 100%">
                                                        @php
                                                            $country_id = 2;
                                                            if($query->personal_info->country_id!=NULL){
                                                                $country_id = $query->personal_info->country_id;
                                                            }
                                                        @endphp
                                                        @foreach($countries as $row)
                                                            @if($country_id==$row->id)
                                                                <option value="{{$row->id}}"selected>{{$row->name}}</option>
                                                            @else
                                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>Citizenship:</label></td>
                                                <td><input type="text"
                                                        class="form-control"
                                                        name="citizenship"
                                                        value="{{$query->personal_info->citizenship}}">
                                                </td>
                                                <td><label>Religion:</label> check if not in list?
                                                    <input type="checkbox"
                                                        name="religion_not_list_check"></td>
                                                <td><select class="form-control select2-info"
                                                        name="religion"
                                                        style="width: 100%">
                                                        @php
                                                            $religion_id = 2;
                                                            if($query->personal_info->religion_id!=NULL){
                                                                $religion_id = $query->personal_info->religion_id;
                                                            }
                                                        @endphp
                                                        @foreach($religions as $row)
                                                            @if($religion_id==$row->id)
                                                                <option value="{{$row->id}}"selected>{{$row->name}}</option>
                                                            @else
                                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>NSTP Serial No.:</label></td>
                                                <td><input type="text" class="form-control"
                                                        name="nstp_serial_no"
                                                        value="@if($query->student_info) {{$query->student_info->nstp_serial_no}} @endif">
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        class="form-control hide"
                                                        name="religion_not_list" placeholder="Please input new religion">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>Branch:</label></td>
                                                <td><select class="form-control select2-info"
                                                        name="branch"
                                                        style="width: 100%">
                                                        @php
                                                            $branch_id = NULL;
                                                            if($query->student_info){
                                                                $branch_id = $query->student_info->program_code->branch_id;
                                                            }
                                                        @endphp
                                                        @foreach($branches as $row)
                                                            @if($branch_id==$row->id)
                                                                <option value="{{$row->id}}"selected>{{$row->name}}</option>
                                                            @else
                                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><label>Department:</label></td>
                                                <td><select class="form-control select2-info"
                                                        name="department"
                                                        style="width: 100%">
                                                        @php
                                                            $department_id = NULL;
                                                            if($query->student_info){
                                                                $department_id = $query->student_info->program->department_id;
                                                            }
                                                        @endphp
                                                        @foreach($departments as $row)
                                                            @if($department_id==$row->id)
                                                                <option value="{{$row->id}}"selected>{{$row->name}}</option>
                                                            @else
                                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>Program:</label></td>
                                                <td><div id="programDiv">
                                                        <select class="form-control select2-info select-info"
                                                            name="program"
                                                            style="width: 100%">

                                                        </select>
                                                    </div>
                                                </td>
                                                <td><label>Curriculum:</label></td>
                                                <td><div id="curriculumDiv">
                                                        <select class="form-control select2-info select-info"
                                                            name="curriculum"
                                                            style="width: 100%">

                                                        </select>
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td><label>Grade Level:</label></td>
                                                <td><div id="gradeLevelDiv">
                                                        <select class="form-control select2-info select-info"
                                                            name="grade_level"
                                                            style="width: 100%">

                                                        </select>
                                                    </div>
                                                </td>
                                                <td><label>Status:</label></td>
                                                <td><select class="form-control select2-info"
                                                        name="student_status"
                                                        style="width: 100%">
                                                        @php
                                                            $student_status_id = NULL;
                                                            if($query->student_info){
                                                                $student_status_id = $query->student_info->student_status_id;
                                                            }
                                                        @endphp
                                                        @foreach($student_statuses as $row)
                                                            @if($student_status_id==$row->id)
                                                                <option value="{{$row->id}}"selected>{{$row->name}}</option>
                                                            @else
                                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                              </div>
                              <div class="tab-pane fade fam-tab @if($val=='Contact') show active @endif"
                                    id="edit-contact" role="tabpanel"
                                    data-val="Contact"
                                    aria-labelledby="custom-tabs-three-profile-tab"
                                    style="min-height: 250px;">
                                <div class="row"
                                    id="address">
                                    <div class="col-lg-12 table-responsive">
                                        <table class="table">
                                            <tr>
                                                <td style="width: 20%"><label>Contact No. 1:</label></td>
                                                <td style="width: 30%">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                          <span class="input-group-text">+63</span>
                                                        </div>
                                                        <input type="text"
                                                            class="form-control contact"
                                                            name="contact_no_1"
                                                            value="{{$query->personal_info->contact_no_official}}">
                                                    </div>
                                                </td>
                                                <td style="width: 20%"><label>Email 1:</label></td>
                                                <td style="width: 30%">
                                                    <input type="email"
                                                            class="form-control"
                                                            name="email_official"
                                                            value="{{$query->personal_info->email_official}}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>Contact No. 2:</label></td>
                                                <td><div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">+63</span>
                                                        </div>
                                                        <input type="text"
                                                            class="form-control contact"
                                                            name="contact_no_2"
                                                            value="{{$query->personal_info->contact_no}}">
                                                    </div>
                                                </td>
                                                <td><label>Email 2:</label></td>
                                                <td><input type="email"
                                                        class="form-control"
                                                        name="email"
                                                        value="{{$query->personal_info->email}}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>Telephone No:</label></td>
                                                <td><div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-phone"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text"
                                                            class="form-control"
                                                            name="telephone_no"
                                                            value="{{$query->personal_info->telephone_no}}">
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </table>
                                        <table class="table">
                                            <tr>
                                                <td colspan="4" class="center">
                                                    <label>Residential Address</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>House/Block/Lot No.</label></td>
                                                <td><input type="text"
                                                        class="form-control"
                                                        name="res_lot"
                                                        value="{{$query->personal_info->res_lot}}">
                                                </td>
                                                <td><label>Street</label></td>
                                                <td><input type="text"
                                                        class="form-control"
                                                        name="res_street"
                                                        value="{{$query->personal_info->res_street}}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>Subdivision/Village</label></td>
                                                <td><input type="text"
                                                        class="form-control"
                                                        name="res_subd"
                                                        value="{{$query->personal_info->res_subd}}">
                                                </td>
                                                <td><label>Province</label></td>
                                                <td>
                                                    <div id="psgcProvinceRes">
                                                        <select class="form-control select2-div psgcProvinceRes"
                                                            name="res_province_id">
                                                            @if($query->personal_info->res_province_id!=NULL)
                                                                <option value="{{$query->personal_info->res_province_id}}">
                                                                    {{$query->personal_info->res_province->name}}
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>City/Municipality</label></td>
                                                <td>
                                                    <div id="psgcCityMunsRes">
                                                        <select class="form-control select2-div psgcCityMunsRes"
                                                            name="res_municipality_id">
                                                            @if($query->personal_info->res_municipality_id!=NULL)
                                                                <option value="{{$query->personal_info->res_municipality_id}}">
                                                                    {{$query->personal_info->res_city_muns->name}}
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td><label>Barangay</label></td>
                                                <td>
                                                    <div id="psgcBrgysRes">
                                                        <select class="form-control select2-div psgcBrgysRes"
                                                            name="res_brgy_id">
                                                            @if($query->personal_info->res_brgy_id!=NULL)
                                                                <option value="{{$query->personal_info->res_brgy_id}}">
                                                                    {{$query->personal_info->res_brgy->name}}
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>Zip Code</label></td>
                                                <td><input type="text"
                                                        class="form-control"
                                                        name="res_zip_code"
                                                        value="{{$query->personal_info->res_zip_code}}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="center">
                                                    <label>Permanent Address</label>
                                                </td>
                                            </tr>
                                            @php
                                                if($query->personal_info->same_res=='Yes'){
                                                    $disabled = 'disabled';
                                                }else{
                                                    $disabled = '';
                                                }
                                            @endphp
                                            <tr>
                                                <td colspan="4" class="center">
                                                    <label>Check if same as Residential Address?
                                                    @if($query->personal_info->same_res=='Yes')
                                                        <input type="checkbox"
                                                            name="same_res"
                                                            value="Yes"
                                                            data-num="0"
                                                            checked>
                                                    @else
                                                        <input type="checkbox"
                                                            name="same_res"
                                                            value="No"
                                                            data-num="0">
                                                    @endif
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>House/Block/Lot No.</label></td>
                                                <td><input type="text"
                                                        class="form-control"
                                                        name="per_lot"
                                                        value="{{$query->personal_info->per_lot}}"
                                                        {{$disabled}}>
                                                </td>
                                                <td><label>Street</label></td>
                                                <td><input type="text"
                                                        class="form-control"
                                                        name="per_street"
                                                        value="{{$query->personal_info->per_street}}"
                                                        {{$disabled}}>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>Subdivision/Village</label></td>
                                                <td><input type="text"
                                                        class="form-control"
                                                        name="per_subd"
                                                        value="{{$query->personal_info->per_subd}}"
                                                        {{$disabled}}>
                                                </td>
                                                <td><label>Province</label></td>
                                                <td>
                                                    <div id="psgcProvincePer">
                                                        <select class="form-control select2-div psgcProvincePer"
                                                            name="per_province_id"
                                                            {{$disabled}}>
                                                            @if($query->personal_info->per_province_id!=NULL)
                                                                <option value="{{$query->personal_info->per_province_id}}">
                                                                    {{$query->personal_info->per_province->name}}
                                                                </option>
                                                            @endif
                                                        </select>
                                                        <select class="hide"
                                                            name="per_province_id_">
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>City/Municipality</label></td>
                                                <td>
                                                    <div id="psgcCityMunsPer">
                                                        <select class="form-control select2-div psgcCityMunsPer"
                                                            name="per_municipality_id"
                                                            {{$disabled}}>
                                                            @if($query->personal_info->per_municipality_id!=NULL)
                                                                <option value="{{$query->personal_info->per_municipality_id}}">
                                                                    {{$query->personal_info->per_city_muns->name}}
                                                                </option>
                                                            @endif
                                                        </select>
                                                        <select class="hide"
                                                            name="per_municipality_id_">
                                                        </select>
                                                    </div>
                                                </td>
                                                <td><label>Barangay</label></td>
                                                <td>
                                                    <div id="psgcBrgysPer">
                                                        <select class="form-control select2-div psgcBrgysPer"
                                                            name="per_brgy_id"
                                                            {{$disabled}}>
                                                            @if($query->personal_info->per_brgy_id!=NULL)
                                                                <option value="{{$query->personal_info->per_brgy_id}}">{{$query->personal_info->per_brgy->name}}</option>
                                                            @endif
                                                        </select>
                                                        <select class="hide"
                                                            name="per_brgy_id_">
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>Zip Code</label></td>
                                                <td><input type="text"
                                                        class="form-control"
                                                        name="per_zip_code"
                                                        value="{{$query->personal_info->per_zip_code}}"
                                                        {{$disabled}}>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                              </div>
                              <div class="tab-pane fade fam-tab @if($val=='Educ') show active @endif"
                                    id="edit-educ"
                                    data-val="Educ"
                                    data-c="{{count($query->education)}}"
                                    role="tabpanel"
                                    aria-labelledby="custom-tabs-three-messages-tab"
                                    style="min-height: 250px;">
                                <div class="row">
                                    <div class="col-8 col-sm-9">
                                        <div class="tab-content" id="vert-tabs-right-tabContent">
                                            <div class="tab-pane fade educ-update-tab"
                                                    id="educ-new"
                                                    data-x="a"
                                                    role="tabpanel"
                                                    aria-labelledby="educ-new-tab">
                                                    <div class="table-responsive">
                                                        <input type="hidden"
                                                            name="educ_id_new"
                                                            value="0">
                                                        <table class="table">
                                                            <tr>
                                                                <td style="width:50%">
                                                                    <label>Level:</label>
                                                                    <select class="form-control select2-info"
                                                                        name="level_new">
                                                                        @foreach($program_level as $row)
                                                                            <option value="{{$row->id}}" data-val="{{$row->program}}">{{$row->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td style="width:50%">
                                                                    <label>School</label> check if not in the list?
                                                                        <input type="checkbox"
                                                                            name="school_not_list_check_new">
                                                                    <div class="school_div_new" id="schoolSearch">
                                                                        <select class="form-control select2-info schoolSearch"
                                                                            name="school_new">

                                                                        </select>
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control hide"
                                                                        name="school_not_list_new"
                                                                        placeholder="Please type new school">
                                                                </td>
                                                            </tr>
                                                            <tr class="hide" id="program_tr_new">
                                                                <td colspan="2"><label>Program</label> check if not in the list?
                                                                        <input type="checkbox"
                                                                            name="program_not_list_check_new">
                                                                    <div class="program_div_new" id="programSearch2">
                                                                        <select class="form-control select2-info programSearch2"
                                                                            name="program_educ_new">

                                                                        </select>
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control hide"
                                                                        name="program_not_list_new"
                                                                        placeholder="Please type new program">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Period From:</label>
                                                                    <input type="text"
                                                                        class="form-control datePicker"
                                                                        name="period_from_new">
                                                                </td>
                                                                <td><label>Period To:</label> check if present?
                                                                    <input type="checkbox"
                                                                            name="period_to_present_new">
                                                                    <input type="text"
                                                                        class="form-control datePicker"
                                                                        name="period_to_new">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Units Earned:</label>
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        name="units_earned_new">
                                                                </td>
                                                                <td><label>Year Graduated:</label>
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        name="year_grad_new">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Honors:</label>
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        name="honors_new">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                            </div>
                                        @if(count($query->education)>0)
                                            @php
                                                $x = 0;
                                            @endphp
                                            @foreach($query->education as $row)
                                                <div class="tab-pane fade educ-update-tab educ-update @if($x==0) show active @endif"
                                                    id="educ-update{{$x}}"
                                                    role="tabpanel"
                                                    data-x="{{$x}}"
                                                    aria-labelledby="educ-update{{$x}}-tab">
                                                    <div class="table-responsive">
                                                        <input type="hidden"
                                                            name="educ_id_update"
                                                            value="{{$row->id}}">
                                                        <table class="table">
                                                            <tr>
                                                                <td style="width:50%">
                                                                    <label>Level:</label>
                                                                    <select class="form-control select2-info"
                                                                        name="level_update"
                                                                        disabled
                                                                        style="width: 100%">
                                                                        <option value="{{$row->level_id}}"
                                                                            data-val="{{$row->level->program}}" selected>
                                                                            {{$row->level->name}}
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                                <td style="width:50%">
                                                                    <label>School</label> not in the list?
                                                                        <input type="checkbox"
                                                                        name="school_not_list_check_update">
                                                                    <div class="school_div_update"
                                                                        id="schoolSearch1{{$x}}">
                                                                        <select class="form-control select2-info schoolSearch1{{$x}}"
                                                                            name="school_update"
                                                                            style="width: 100%">
                                                                            <option value="{{$row->school->id}}">{{$row->school->name}}</option>
                                                                        </select>
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control hide"
                                                                        name="school_not_list_update"
                                                                        placeholder="Please type new school">
                                                                    <input type="text"
                                                                        class="form-control hide"
                                                                        name="school_shorten_not_list_update"
                                                                        placeholder="Please type new school shorten">
                                                                </td>
                                                            </tr>
                                                            <tr @if($row->level->program!='w') class="hide" @endif
                                                                id="program_tr_update">
                                                                <td colspan="2"><label>Program</label> not in the list?
                                                                        <input type="checkbox"
                                                                            name="program_not_list_check_update">
                                                                    <div class="program_div_update"
                                                                        id="programSearch2{{$x}}">
                                                                        <select class="form-control select2-info programSearch2{{$x}}"
                                                                            name="program_educ_update"
                                                                            style="width: 100%">
                                                                            @if($row->program)
                                                                                <option value="{{$row->program->id}}">{{$row->program->name}}</option>
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control hide"
                                                                        name="program_not_list_update"
                                                                        placeholder="Please type new program">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Period From:</label>
                                                                    <input type="text"
                                                                        class="form-control datePicker"
                                                                        name="period_from_update"
                                                                        value="{{date('m/d/Y',strtotime($row->period_from))}}">
                                                                </td>
                                                                <td><label>Period To:</label> check if present?
                                                                    <input type="checkbox"
                                                                            name="period_to_present_update"
                                                                            @if($row->period_to==NULL) checked @endif>
                                                                    <input type="text"
                                                                        class="form-control datePicker"
                                                                        name="period_to_update"
                                                                        value="@if($row->period_to!=NULL) {{date('m/d/Y',strtotime($row->period_to))}} @endif"
                                                                        @if($row->period_to==NULL) readonly @endif>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Units Earned:</label>
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        name="units_earned_update"
                                                                        value="{{$row->units_earned}}">
                                                                </td>
                                                                <td><label>Year Graduated:</label>
                                                                    <input type="text"
                                                                         class="form-control"
                                                                         name="year_grad_update"
                                                                         value="{{$row->year_grad}}">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Honors:</label>
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        name="honors_update"
                                                                        value="{{$row->honors}}">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            @php
                                                    $x++;
                                                @endphp
                                            @endforeach
                                        @endif
                                        </div>
                                    </div>
                                    <div class="col-4 col-sm-3">
                                      <div class="nav flex-column nav-tabs nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link"
                                            id="educ-new-tab"
                                            data-toggle="pill"
                                            href="#educ-new"
                                            role="tab"
                                            aria-controls="educ-new"
                                            aria-selected="true">
                                            New
                                        </a>
                                        @if(count($query->education)>0)
                                            @php
                                                $x = 0;
                                            @endphp
                                            @foreach($query->education as $row)
                                                <a class="nav-link tab-update @if($x==0) active @endif"
                                                        id="educ-update{{$x}}-tab"
                                                        data-toggle="pill"
                                                        data-x="{{$x}}"
                                                        href="#educ-update{{$x}}"
                                                        role="tab"
                                                        aria-controls="educ-update{{$x}}"
                                                        aria-selected="true">
                                                    {{ucwords(mb_strtolower($row->level->name))}}
                                                </a>
                                                @php
                                                    $x++;
                                                @endphp
                                            @endforeach
                                        @endif
                                      </div>
                                    </div>
                                </div>
                              </div>
                              <div class="tab-pane fade fam-tab @if($val=='Fam') show active @endif"
                                    id="edit-fam"
                                    data-val="Fam"
                                    role="tabpanel"
                                    aria-labelledby="custom-tabs-three-settings-tab"
                                    style="min-height: 250px;">
                                <div class="row">
                                    <div class="col-8 col-sm-10">
                                        <div class="tab-content" id="vert-tabs-right-tabContent">
                                            <div class="tab-pane fade fam-update-tab"
                                                    id="fam-new"
                                                    data-x="a"
                                                    role="tabpanel"
                                                    aria-labelledby="fam-new-tab">
                                                <input type="hidden"
                                                    name="fam_id"
                                                    value="0">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <tr>
                                                            <td style="width: 33.33%">
                                                                <label>Relation:</label>
                                                                <select class="form-control select2-info"
                                                                    name="fam_relation"
                                                                    style="width: 100%">
                                                                        @foreach($relations as $row)
                                                                            <option value="{{$row->id}}">
                                                                                {{$row->name}}
                                                                            </option>
                                                                        @endforeach
                                                                </select>
                                                            </td>
                                                            <td style="width: 33.33%">
                                                                <label>Lastname:</label>
                                                                <input type="text"
                                                                    class="form-control"
                                                                    name="fam_lastname">
                                                            </td>
                                                            <td style="width: 33.33%">
                                                                <label>Firstname:</label>
                                                                <input type="text"
                                                                    class="form-control"
                                                                    name="fam_firstname">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>Middlename:</label>
                                                                <input type="text"
                                                                    class="form-control"
                                                                    name="fam_middlename">
                                                            </td>
                                                            <td>
                                                                <label>Extname:</label>
                                                                <input type="text"
                                                                    class="form-control"
                                                                    name="fam_extname">
                                                            </td>
                                                            <td>
                                                                <label>Birthdate:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </span>
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control datePicker"
                                                                        name="fam_dob"
                                                                        value="">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Contact:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                      <span class="input-group-text">+63</span>
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control contact"
                                                                        name="fam_contact_no"
                                                                        value="">
                                                                </div>
                                                            </td>
                                                            <td><label>Email:</label>
                                                                <input type="email"
                                                                    class="form-control"
                                                                    name="fam_email"
                                                                    value="">
                                                            </td>
                                                            <td><label>Occupation:</label>
                                                                <input type="text"
                                                                    class="form-control"
                                                                    name="fam_occupation"
                                                                    value="">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Employer:</label>
                                                                <input type="text"
                                                                    class="form-control"
                                                                    name="fam_employer"
                                                                    value="">
                                                            </td>
                                                            <td><label>Employer Address:</label>
                                                                <input type="text"
                                                                    class="form-control"
                                                                    name="fam_employer_address"
                                                                    value="">
                                                            </td>
                                                            <td><label>Employer Contact:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                      <span class="input-group-text">+63</span>
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control contact"
                                                                        name="fam_employer_contact"
                                                                        value="">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        @if(count($query->family)>0)
                                            @php
                                                $x = 0;
                                            @endphp
                                            @foreach($query->family as $row)
                                                <div class="tab-pane fade fam-update-tab fam-update @if($x==0) show active @endif"
                                                    id="fam-update-{{$x}}"
                                                    data-x="{{$x}}"
                                                    role="tabpanel"
                                                    aria-labelledby="fam-update-{{$x}}-tab">
                                                    <input type="hidden"
                                                            name="fam_id"
                                                            value="{{$row->id}}">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <tr>
                                                                <td style="width: 33.33%">
                                                                    <label>Relation:</label>
                                                                    <select class="form-control select2-info"
                                                                        name="fam_relation"
                                                                        disabled
                                                                        style="width: 100%">
                                                                            <option value="{{$row->fam_relation->id}}">
                                                                                {{$row->fam_relation->name}}
                                                                            </option>
                                                                    </select>
                                                                </td>
                                                                <td style="width: 33.33%">
                                                                    <label>Lastname:</label>
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        name="fam_lastname"
                                                                        value="{{$row->lastname}}">
                                                                </td>
                                                                <td style="width: 33.33%">
                                                                    <label>Firstname:</label>
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        name="fam_firstname"
                                                                        value="{{$row->firstname}}">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <label>Middlename:</label>
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        name="fam_middlename"
                                                                        value="{{$row->middlename}}">
                                                                </td>
                                                                <td>
                                                                    <label>Extname:</label>
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        name="fam_extname"
                                                                        value="{{$row->extname}}">
                                                                </td>
                                                                <td>
                                                                    <label>Birthdate:</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </span>
                                                                        </div>
                                                                        <input type="text"
                                                                            class="form-control datePicker"
                                                                            name="fam_dob"
                                                                            value="@if(strtotime($row->dob) !== false) {{date('m/d/Y',strtotime($row->dob))}} @endif">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Contact:</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                          <span class="input-group-text">+63</span>
                                                                        </div>
                                                                        <input type="text"
                                                                            class="form-control contact"
                                                                            name="fam_contact_no"
                                                                            value="{{$row->contact_no}}">
                                                                    </div>
                                                                </td>
                                                                <td><label>Email:</label>
                                                                    <input type="email"
                                                                        class="form-control"
                                                                        name="fam_email"
                                                                        value="{{$row->email}}">
                                                                </td>
                                                                <td><label>Occupation:</label>
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        name="fam_occupation"
                                                                        value="{{$row->occupation}}">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><label>Employer:</label>
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        name="fam_employer"
                                                                        value="{{$row->employer}}">
                                                                </td>
                                                                <td><label>Employer Address:</label>
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        name="fam_employer_address"
                                                                        value="{{$row->employer_address}}">
                                                                </td>
                                                                <td><label>Employer Contact:</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                          <span class="input-group-text">+63</span>
                                                                        </div>
                                                                        <input type="text"
                                                                            class="form-control contact"
                                                                            name="fam_employer_contact"
                                                                            value="{{$row->employer_contact}}">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                @php
                                                    $x++;
                                                @endphp
                                            @endforeach
                                        @endif
                                        </div>
                                    </div>
                                    <div class="col-4 col-sm-2">
                                      <div class="nav flex-column nav-tabs nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link"
                                                id="fam-new-tab"
                                                data-toggle="pill"
                                                href="#fam-new"
                                                role="tab"
                                                aria-controls="fam-new"
                                                aria-selected="true">
                                            New
                                        </a>
                                        @if(count($query->family)>0)
                                            @php
                                                $x = 0;
                                            @endphp
                                            @foreach($query->family as $row)
                                                <a class="nav-link fam-update @if($x==0) active @endif"
                                                        id="fam-update-{{$x}}-tab"
                                                        data-x="{{$x}}"
                                                        data-toggle="pill"
                                                        href="#fam-update-{{$x}}"
                                                        role="tab"
                                                        aria-controls="fam-update-{{$x}}"
                                                        aria-selected="true">
                                                    {{$row->fam_relation->name}}
                                                </a>
                                                @php
                                                    $x++;
                                                @endphp
                                            @endforeach
                                        @endif
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <button type="button"
                                class="btn btn-success btn-lg btn-success-scan"
                                id="submitInfo"
                                style="width: 100%">
                                <span class="fa fa-save"></span> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button"
            class="btn btn-default"
            data-dismiss="modal">Close
        </button>
    </div>
</div>
<script src="{{ asset('assets/js/search/programSearch.js') }}"></script>
<script src="{{ asset('assets/js/search/school1Search.js') }}"></script>
<script src="{{ asset('assets/js/search/psgc_provinces.js') }}"></script>
<script src="{{ asset('assets/js/search/psgc_city_muns.js') }}"></script>
<script src="{{ asset('assets/js/search/psgc_brgys.js') }}"></script>
<script src="{{ asset('assets/js/rims/student/studentInfo.js') }}"></script>
<script src="{{ asset('assets/js/rims/student/studentAddress.js') }}"></script>

