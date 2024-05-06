<div class="card card-primary card-tabs">
    <div class="card-header p-0 pt-1">
      <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="pill" href="#info" role="tab" aria-selected="true">Info</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="pill" href="#address" role="tab" aria-selected="false">Address</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="pill" href="#id_no" role="tab" aria-selected="false">ID Nos.</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content">
        <div class="tab-pane fade show active" id="info" role="tabpanel">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                    <span class="text-require">*</span> <span style="font-size:12px">Required Field</span>
                    <table class="table">
                        <tr>
                            <td style="width: 20%"><label>Horrific Name:</label></td>
                            <td style="width: 30%"><input type="text" class="form-control" name="honorific" value="{{$query->honorific}}" placeholder="Dr., Atty., Engr."></td>
                            <td style="width: 20%"><label>Post-Nominal Title:</label></td>
                            <td style="width: 30%"><input type="text" class="form-control" name="post_nominal" value="{{$query->post_nominal}}" placeholder="PhD., MBA, MA, CPA, MIS, RN"></td>
                        </tr>
                        <tr>
                            <td><label>Lastname:<span class="text-require">*</span></label></td>
                            <td><input type="text" class="form-control" name="lastname" value="{{$query->lastname}}"></td>
                            <td><label>Firstname:<span class="text-require">*</span></label></td>
                            <td><input type="text" class="form-control" name="firstname" value="{{$query->firstname}}"></td>
                        </tr>
                        <tr>
                            <td><label>Middlename:</label></td>
                            <td><input type="text" class="form-control" name="middlename" value="{{$query->middlename}}"></td>
                            <td><label>Extname:</label></td>
                            <td><input type="text" class="form-control" name="extname" value="{{$query->extname}}"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="center">Include Middlename in lastname?:<span class="text-require">*</span><br>
                                Ex.: <label>{{$query->lastname}}-{{$query->middlename}}</label>
                            </td>
                            <td colspan="2">
                                <select class="form-control select2-div" name="middlename_in_last">
                                    @if($query->middlename_in_last=='Y')
                                        <option value="N">No</option>
                                        <option value="Y" selected>Yes</option>
                                    @else
                                        <option value="N" selected>No</option>
                                        <option value="Y">Yes</option>
                                    @endif
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Birthdate:<span class="text-require">*</span></label></td>
                            <td>
                                <div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" class="form-control datePicker" name="dob" value="{{date('m/d/Y',strtotime($query->personal_info->dob))}}">
                                    </div>
                                </div>
                            </td>
                            <td><label>Place of Birth:</label></td>
                            <td>
                                <div id="psgcCityMunsPlace">
                                    <select class="form-control select2-div psgcCityMunsPlace" name="place_birth">
                                        <option value="{{$query->place_birth}}">{{$query->place_birth}}</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Sex:<span class="text-require">*</span></label></td>
                            <td><select class="form-control select2-div" name="sex">
                                    @foreach($sexs as $row)
                                        @if($row->id==$query->personal_info->sex)
                                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                        @else
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endif
                                    @endforeach
                                </select></td>
                            <td><label>Civil Status:<span class="text-require">*</span></label></td>
                            <td><select class="form-control select2-div" name="civil_status">
                                @foreach($civil_statuses as $row)
                                    @if($row->id==$query->personal_info->civil_status_id)
                                        <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                    @else
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endif
                                @endforeach
                            </select></td>
                        </tr>
                        <tr>
                            <td><label>Height (m):</label></td>
                            <td><input type="number" class="form-control" name="height" value="{{$query->personal_info->height}}"></td>
                            <td><label>Weight (kg):</label></td>
                            <td><input type="number" class="form-control" name="weight" value="{{$query->personal_info->weight}}"></td>
                        </tr>
                        <tr>
                            <td><label>Blood Type:</label></td>
                            <td><select class="form-control select2-div" name="blood_type">
                                    @foreach($blood_types as $row)
                                        @if($row->id==$query->personal_info->blood_type_id)
                                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                        @else
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td><label>Telephone No.:</label></td>
                            <td><div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="telephone_no" value="{{$query->personal_info->telephone_no}}">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Contact No.:</label></td>
                            <td><div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-append">
                                            <span class="input-group-text">(+63)</span>
                                        </div>
                                        <input type="text" class="form-control contact" name="contact_no" value="{{$query->personal_info->contact_no}}">
                                    </div>
                                </div>
                            </td>
                            <td><label>Contact No.(Official):</label></td>
                            <td><div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-append">
                                            <span class="input-group-text">(+63)</span>
                                        </div>
                                        <input type="text" class="form-control contact" name="contact_no_official" value="{{$query->personal_info->contact_no_official}}">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Email:</label></td>
                            <td><div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="email" value="{{$query->personal_info->email}}">
                                    </div>
                                </div>
                            </td>
                            <td><label>Email(Official):</label></td>
                            <td><div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="email_official" value="{{$query->personal_info->email_official}}">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <button class="btn btn-info btn-info-scan" name="submit" style="width: 100%">
                                    <span class="fa fa-save"></span> Save
                                </button>
                            </td>
                        </tr>
                    </table>
                    @else
                    <table class="table">
                        <tr>
                            <td style="width: 20%"><label>Lastname:</label></td>
                            <td style="width: 30%">{{$query->lastname}}</td>
                            <td style="width: 20%"><label>Firstname:</label></td>
                            <td style="width: 30%">{{$query->firstname}}</td>
                        </tr>
                        <tr>
                            <td><label>Middlename:</label></td>
                            <td>{{$query->middlename}}</td>
                            <td><label>Extname:</label></td>
                            <td>{{$query->extname}}</td>
                        </tr>
                        <tr>
                            <td><label>Birthdate:</label></td>
                            <td><div class="input-group input-group-sm">
                                    <div class="input-group-append">
                                        <span class="fa fa-calendar"></span>
                                    </div>
                                    {{$query->personal_info->dob}}
                                </div>
                            </td>
                            <td><label>Place of Birth:</label></td>
                            <td>
                                {{$query->place_birth}}
                            </td>
                        </tr>
                        <tr>
                            <td><label>Sex:</label></td>
                            <td>@if($query->personal_info->sex!=NULL)
                                {{$query->personal_info->sexs->name}}
                                @endif
                            </td>
                            <td><label>Civil Status:</label></td>
                            <td>@if($query->personal_info->civil_status_id!=NULL)
                                {{$query->personal_info->civil_statuses->name}}
                                @endif</td>
                        </tr>
                        <tr>
                            <td><label>Height (m):</label></td>
                            <td>{{$query->personal_info->height}}</td>
                            <td><label>Weight (kg):</label></td>
                            <td>{{$query->personal_info->weight}}</td>
                        </tr>
                        <tr>
                            <td><label>Blood Type:</label></td>
                            <td>@if($query->personal_info->blood_type_id!=NULL)
                                {{$query->personal_info->blood->name}}
                                @endif
                            </td>
                            <td><label>Telephone No.:</label></td>
                            <td><div class="input-group input-group-sm">
                                    <div class="input-group-append">
                                        <span class="fa fa-phone"></span>
                                    </div>
                                    {{$query->personal_info->telephone_no}}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Contact No.:</label></td>
                            <td><div class="input-group input-group-sm">
                                    <div class="input-group-append">
                                        (+63)
                                    </div>
                                    {{$query->personal_info->contact_no}}
                                </div>
                            </td>
                            <td><label>Contact No.(Official):</label></td>
                            <td><div class="input-group input-group-sm">
                                    <div class="input-group-append">
                                        (+63)
                                    </div>
                                    {{$query->personal_info->contact_no_official}}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Email:</label></td>
                            <td>{{$query->personal_info->email}}
                            </td>
                            <td><label>Email(Official):</label></td>
                            <td>{{$query->personal_info->email_official}}
                            </td>
                        </tr>
                    </table>
                    @endif
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="address" role="tabpanel">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                    <table class="table">
                        <tr>
                            <td colspan="4" class="center">
                                <label>Residential Address</label>
                            </td>
                        </tr>
                        <tr>
                            <td><label>House/Block/Lot No.</label></td>
                            <td><input type="text" class="form-control" name="res_lot" value="{{$query->personal_info->res_lot}}"></td>
                            <td><label>Street</label></td>
                            <td><input type="text" class="form-control" name="res_street" value="{{$query->personal_info->res_street}}"></td>
                        </tr>
                        <tr>
                            <td><label>Subdivision/Village</label></td>
                            <td><input type="text" class="form-control" name="res_subd" value="{{$query->personal_info->res_subd}}"></td>
                            <td><label>Province</label></td>
                            <td>
                                <div id="psgcProvinceRes">
                                    <select class="form-control select2-div psgcProvinceRes" name="res_province_id">
                                        @if($query->personal_info->res_province_id!=NULL)
                                        <option value="{{$query->personal_info->res_province_id}}">{{$query->personal_info->res_province->name}}</option>
                                        @endif
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>City/Municipality</label></td>
                            <td>
                                <div id="psgcCityMunsRes">
                                    <select class="form-control select2-div psgcCityMunsRes" name="res_municipality_id">
                                        @if($query->personal_info->res_municipality_id!=NULL)
                                        <option value="{{$query->personal_info->res_municipality_id}}">{{$query->personal_info->res_city_muns->name}}</option>
                                        @endif
                                    </select>
                                </div>
                            </td>
                            <td><label>Barangay</label></td>
                            <td>
                                <div id="psgcBrgysRes">
                                    <select class="form-control select2-div psgcBrgysRes" name="res_brgy_id">
                                        @if($query->personal_info->res_brgy_id!=NULL)
                                        <option value="{{$query->personal_info->res_brgy_id}}">{{$query->personal_info->res_brgy->name}}</option>
                                        @endif
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Zip Code</label></td>
                            <td><input type="text" class="form-control" name="res_zip_code" value="{{$query->personal_info->res_zip_code}}"></td>
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
                                <input type="checkbox" name="same_res" value="Yes" data-num="0" checked>
                                @else
                                <input type="checkbox" name="same_res" value="No" data-num="0">
                                @endif
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td><label>House/Block/Lot No.</label></td>
                            <td><input type="text" class="form-control" name="per_lot" value="{{$query->personal_info->per_lot}}" {{$disabled}}></td>
                            <td><label>Street</label></td>
                            <td><input type="text" class="form-control" name="per_street" value="{{$query->personal_info->per_street}}" {{$disabled}}></td>
                        </tr>
                        <tr>
                            <td><label>Subdivision/Village</label></td>
                            <td><input type="text" class="form-control" name="per_subd" value="{{$query->personal_info->per_subd}}" {{$disabled}}></td>
                            <td><label>Province</label></td>
                            <td>
                                <div id="psgcProvincePer">
                                    <select class="form-control select2-div psgcProvincePer" name="per_province_id" {{$disabled}}>
                                        @if($query->personal_info->per_province_id!=NULL)
                                        <option value="{{$query->personal_info->per_province_id}}">{{$query->personal_info->per_province->name}}</option>
                                        @endif
                                    </select>
                                    <select class="hide" name="per_province_id_">
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>City/Municipality</label></td>
                            <td>
                                <div id="psgcCityMunsPer">
                                    <select class="form-control select2-div psgcCityMunsPer" name="per_municipality_id" {{$disabled}}>
                                        @if($query->personal_info->per_municipality_id!=NULL)
                                        <option value="{{$query->personal_info->per_municipality_id}}">{{$query->personal_info->per_city_muns->name}}</option>
                                        @endif
                                    </select>
                                    <select class="hide" name="per_municipality_id_">
                                    </select>
                                </div>
                            </td>
                            <td><label>Barangay</label></td>
                            <td>
                                <div id="psgcBrgysPer">
                                    <select class="form-control select2-div psgcBrgysPer" name="per_brgy_id" {{$disabled}}>
                                        @if($query->personal_info->per_brgy_id!=NULL)
                                        <option value="{{$query->personal_info->per_brgy_id}}">{{$query->personal_info->per_brgy->name}}</option>
                                        @endif
                                    </select>
                                    <select class="hide" name="per_brgy_id_">
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Zip Code</label></td>
                            <td><input type="text" class="form-control" name="per_zip_code" value="{{$query->personal_info->per_zip_code}}" {{$disabled}}></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <button class="btn btn-info btn-info-scan" name="submit" style="width: 100%">
                                    <span class="fa fa-save"></span> Save
                                </button>
                            </td>
                        </tr>
                    </table>
                    <br><br><br><br><br><br>
                    @else
                    <table class="table">
                        <tr>
                            <td colspan="4" class="center">
                                <label>Residential Address</label>
                            </td>
                        </tr>
                        <tr>
                            <td><label>House/Block/Lot No.</label></td>
                            <td>{{$query->personal_info->res_lot}}</td>
                            <td><label>Street</label></td>
                            <td>{{$query->personal_info->res_street}}</td>
                        </tr>
                        <tr>
                            <td><label>Subdivision/Village</label></td>
                            <td>{{$query->personal_info->res_subd}}</td>
                            <td><label>Barangay</label></td>
                            <td>@if($query->personal_info->res_brgy_id!=NULL)
                                {{$query->personal_info->res_brgy->name}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><label>City/Municipality</label></td>
                            <td>@if($query->personal_info->res_municipality_id!=NULL)
                                {{$query->personal_info->res_city_muns->name}}
                                @endif
                            </td>
                            <td><label>Province</label></td>
                            <td>@if($query->personal_info->res_province_id!=NULL)
                                {{$query->personal_info->res_province->name}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><label>Zip Code</label></td>
                            <td>{{$query->personal_info->res_zip_code}}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="center">
                                <label>Permanent Address</label>
                            </td>
                        </tr>
                        <tr>
                            <td><label>House/Block/Lot No.</label></td>
                            <td>{{$query->personal_info->per_lot}}</td>
                            <td><label>Street</label></td>
                            <td>{{$query->personal_info->per_street}}</td>
                        </tr>
                        <tr>
                            <td><label>Subdivision/Village</label></td>
                            <td>{{$query->personal_info->per_subd}}</td>
                            <td><label>Barangay</label></td>
                            <td>@if($query->personal_info->per_brgy_id!=NULL)
                                {{$query->personal_info->per_brgy->name}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><label>City/Municipality</label></td>
                            <td>@if($query->personal_info->per_municipality_id!=NULL)
                                {{$query->personal_info->per_city_muns->name}}
                                @endif
                            </td>
                            <td><label>Province</label></td>
                            <td>@if($query->personal_info->per_province_id!=NULL)
                                {{$query->personal_info->per_province->name}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><label>Zip Code</label></td>
                            <td>{{$query->personal_info->per_zip_code}}</td>
                        </tr>
                    </table>
                    @endif
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="id_no" role="tabpanel">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                    <table class="table">
                        <tr>
                            <td style="width: 25%"><label>Agency Employee No.</label></td>
                            <td style="width: 25%">
                                {{$query->id_no}}</td>
                            <td style="width: 25%"><label>Bank Account No.</label></td>
                            <td style="width: 25%">
                                <input type="text" class="form-control bank_account_no" name="bank_account_no"
                                    value="{{$query->personal_info->bank_account_no}}"></td>
                        </tr>
                        <tr>
                            <td><label>TIN No.</label></td>
                            <td>
                                <input type="text" class="form-control tin_no" name="tin_no"
                                    value="{{$query->personal_info->tin_no}}"></td>
                            <td><label>GSIS ID No.</label></td>
                            <td><input type="text" class="form-control gsis_bp_no" name="gsis_bp_no"
                                value="{{$query->personal_info->gsis_bp_no}}"></td>
                        </tr>
                        <tr>
                            <td><label>Philhealth No.</label></td>
                            <td><input type="text" class="form-control philhealth_no" name="philhealth_no"
                                value="{{$query->personal_info->philhealth_no}}"></td>
                            <td><label>SSS No.</label></td>
                            <td><input type="text" class="form-control sss_no" name="sss_no"
                                value="{{$query->personal_info->sss_no}}"></td>
                        </tr>
                        <tr>
                            <td><label>PAGIBIG ID No.</label></td>
                            <td><input type="text" class="form-control pagibig_no" name="pagibig_no"
                                value="{{$query->personal_info->pagibig_no}}"></td>
                            <td><label>PAGIBIG II ID No.</label></td>
                            <td><input type="text" class="form-control" name="pagibig2_no"
                                value="{{$query->personal_info->pagibig2_no}}"></td>
                        </tr>
                        <tr>
                            <td colspan="4"><center><label>PAGIBIG APPLICATION NO.</label></center></td>
                        </tr>
                        <tr>
                            <td><label>MPL</label></td>
                            <td><input type="text" class="form-control" name="pagibig_mpl_app_no"
                                value="{{$query->personal_info->pagibig_mpl_app_no}}"></td>
                            <td><label>CALAMITY</label></td>
                            <td><input type="text" class="form-control" name="pagibig_cal_app_no"
                                value="{{$query->personal_info->pagibig_cal_app_no}}"></td>
                        </tr>
                        <tr>
                            <td><label>HOUSING</label></td>
                            <td><input type="text" class="form-control" name="pagibig_housing_app_no"
                                value="{{$query->personal_info->pagibig_housing_app_no}}"></td>
                            <td><label>PAGIBIG II</label></td>
                            <td><input type="text" class="form-control" name="pagibig_pag2_app_no"
                                value="{{$query->personal_info->pagibig_pag2_app_no}}"></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <button class="btn btn-info btn-info-scan" name="submit" style="width: 100%">
                                    <span class="fa fa-save"></span> Save
                                </button>
                            </td>
                        </tr>
                    </table>
                    @else
                    <table class="table">
                        <tr>
                            <td style="width: 25%"><label>Agency Employee No.</label></td>
                            <td style="width: 25%">
                                {{$query->id_no}}</td>
                            <td style="width: 25%"><label>Bank Account No.</label></td>
                            <td style="width: 25%">{{$query->personal_info->bank_account_no}}</td>
                        </tr>
                        <tr>
                            <td><label>TIN No.</label></td>
                            <td>{{$query->personal_info->tin_no}}</td>
                            <td><label>GSIS ID No.</label></td>
                            <td>{{$query->personal_info->gsis_bp_no}}</td>
                        </tr>
                        <tr>
                            <td><label>Philhealth No.</label></td>
                            <td>{{$query->personal_info->philhealth_no}}</td>
                            <td><label>SSS No.</label></td>
                            <td>{{$query->personal_info->sss_no}}</td>
                        </tr>
                        <tr>
                            <td><label>PAGIBIG ID No.</label></td>
                            <td>{{$query->personal_info->pagibig_no}}</td>
                            <td><label>PAGIBIG II ID No.</label></td>
                            <td>{{$query->personal_info->pagibig2_no}}</td>
                        </tr>
                        <tr>
                            <td colspan="4"><center><label>PAGIBIG APPLICATION NO.</label></center></td>
                        </tr>
                        <tr>
                            <td><label>MPL</label></td>
                            <td>{{$query->personal_info->pagibig_mpl_app_no}}</td>
                            <td><label>CALAMITY</label></td>
                            <td>{{$query->personal_info->pagibig_cal_app_no}}</td>
                        </tr>
                        <tr>
                            <td><label>HOUSING</label></td>
                            <td>{{$query->personal_info->pagibig_housing_app_no}}</td>
                            <td><label>PAGIBIG II</label></td>
                            <td>{{$query->personal_info->pagibig_pag2_app_no}}</td>
                        </tr>
                    </table>
                    @endif
                </div>
            </div>
        </div>
      </div>
    </div>
    <!-- /.card -->
  </div>
  @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
  <script src="{{ asset('assets/js/search/psgc_brgys.js') }}"></script>
  <script src="{{ asset('assets/js/search/psgc_city_muns.js') }}"></script>
  <script src="{{ asset('assets/js/search/psgc_provinces.js') }}"></script>
  <script src="{{ asset('assets/js/hrims/employee/information/personal_info.js') }}"></script>
  @endif
