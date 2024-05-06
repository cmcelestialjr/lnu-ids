<div class="card card-primary card-tabs">
    <div class="card-header p-0 pt-1">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item informationEditDiv" data-id="personalInfoEdit">
          <a class="nav-link active" data-toggle="pill" href="#address" role="tab" aria-selected="true">Personal Info</a>
        </li>
        <li class="nav-item informationEditDiv" data-id="educationalBgEdit">
          <a class="nav-link" data-toggle="pill" href="#educBg" role="tab" aria-selected="false">Educational Background</a>
        </li>
        <li class="nav-item informationEditDiv" data-id="familyBgEdit">
            <a class="nav-link" data-toggle="pill" href="#famBg" role="tab" aria-selected="false">Family Background</a>
          </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content">
        <div class="tab-pane fade show active" id="address" role="tabpanel">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table">
                        <tr>
                            <td style="width: 15%"><label>Sex:<span class="text-require">*</span></label></td>
                            <td style="width: 35%">
                                <select class="form-control select2-div" name="sex" style="width: 100%">
                                    @foreach($sexs as $row)
                                        @if($row->id==$query->personal_info->sex)
                                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                        @else
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endif
                                    @endforeach
                                </select></td> 
                            <td style="width: 15%"><label>Civil Status:<span class="text-require">*</span></label></td>
                            <td style="width: 35%">
                                <select class="form-control select2-div" name="civil_status" style="width: 100%">
                                    @foreach($civil_statuses as $row)
                                        @if($row->id==$query->personal_info->civil_status_id)
                                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                        @else
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Contact No1.:</label></td>
                            <td><div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-append">
                                            <span class="input-group-text">(+63)</span>
                                        </div>
                                        <input type="text" class="form-control contact" name="contact_no" value="{{$query->personal_info->contact_no}}">
                                    </div>
                                </div>
                            </td>
                            <td><label>Contact No2.:</label></td>
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
                            <td><label>Email1:</label></td>
                            <td><div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="email" value="{{$query->personal_info->email}}">
                                    </div>
                                </div>
                            </td>
                            <td><label>Email2:</label></td>
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
                            <td><label>Blood Type:</label></td>
                            <td><select class="form-control select2-div" name="blood_type" style="width: 100%">
                                    @foreach($blood_types as $row)
                                        @if($row->id==$query->personal_info->blood_type_id)
                                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                        @else
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td> 
                            <td><label>Religion:</label> <label>not in list? <input type="checkbox" name="check_religion" id="new_religion"></label></td>
                            <td>
                                <div id="list_religion_div">
                                    <select class="form-control select2-div" name="religion" style="width: 100%">
                                        @foreach($religions as $row)
                                            @if($row->id==$query->personal_info->religion_id)
                                                <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                            @else
                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="hide" id="new_religion_div">
                                    <input type="text" class="form-control" name="new_religion" placeholder="Input new Religion">
                                </div>
                            </td> 
                        </tr>
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
                                    <select class="form-control select2-div psgcProvinceRes" name="res_province_id" style="width: 100%">
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
                                    <select class="form-control select2-div psgcCityMunsRes" name="res_municipality_id" style="width: 100%">
                                        @if($query->personal_info->res_municipality_id!=NULL)
                                        <option value="{{$query->personal_info->res_municipality_id}}">{{$query->personal_info->res_city_muns->name}}</option>
                                        @endif
                                    </select>
                                </div>
                            </td>
                            <td><label>Barangay</label></td>
                            <td>
                                <div id="psgcBrgysRes">
                                    <select class="form-control select2-div psgcBrgysRes" name="res_brgy_id" style="width: 100%">
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
                                    <select class="form-control select2-div psgcProvincePer" name="per_province_id" {{$disabled}} style="width: 100%">
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
                                    <select class="form-control select2-div psgcCityMunsPer" name="per_municipality_id" {{$disabled}} style="width: 100%">
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
                                    <select class="form-control select2-div psgcBrgysPer" name="per_brgy_id" {{$disabled}} style="width: 100%">
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
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
<script src="{{ asset('assets/js/search/psgc_brgys.js') }}"></script>
<script src="{{ asset('assets/js/search/psgc_city_muns.js') }}"></script>
<script src="{{ asset('assets/js/search/psgc_provinces.js') }}"></script>
<script src="{{ asset('assets/js/sims/information/personal_info.js') }}"></script>