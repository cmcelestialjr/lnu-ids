
<div class="modal-content" id="studentViewModal">
    <div class="modal-header">
        <h4 class="modal-title"></h4>
        <span class="fa fa-times" data-dismiss="modal"></span>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <input type="hidden" name="program_level" value="{{$program_level}}">
            <input type="hidden" name="curriculum" value="{{$curriculum}}">
            <div class="col-lg-12">
                <div class="card card-primary card-outline"\>
                        <div class="row">
                            <div class="col-lg-3 col-sm-12 center"><br><br><br>
                                <img src="{{ asset('assets/images/icons/png/user.png') }}" class="profile-picture" alt="Student Image">
                            </div>
                            <div class="col-lg-9 col-sm-12">
                                <div class="card-header p-0 pt-1 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist" style="font-size: 12px;">
                                      <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#personal-info" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Personal Info</a>
                                      </li>
                                      <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Contact & Address</a>
                                      </li>
                                      <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Educational Background</a>
                                      </li>
                                      <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-three-settings-tab" data-toggle="pill" href="#custom-tabs-three-settings" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Family Background</a>
                                      </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-three-tabContent">
                                      <div class="tab-pane fade show active" id="personal-info" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab" style="min-height: 250px;">
                                        <div class="row">
                                            <div class="col-12">
                                                <button class="btn btn-info btn-info-scan btn-xs studentInfoEdit"
                                                    data-val="Info" style="float:right">
                                                    <span class="fa fa-edit"> Edit</span>
                                                </button>
                                            </div>
                                            <div class="col-lg-12 table-responsive">
                                                <table class="table">
                                                    <tr>
                                                        <td>Name:</td>
                                                        <td colspan="3"><label>{{$query->lastname}}, {{$query->firstname}} {{$query->extname}} {{$query->middlename}}</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 10%">ID No.:</td>
                                                        <td style="width: 20%"><label>{{$query->stud_id}}</label></td>
                                                        <td style="width: 10%">Nickname:</td>
                                                        <td style="width: 20%"><label>{{$query->firstname}}</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sex:</td>
                                                        <td><label>
                                                            @if($query->personal_info->sex!=NULL)
                                                            {{$query->personal_info->sexs->name}}
                                                            @endif
                                                            </label>
                                                        </td>
                                                        <td>Civil Status:</td>
                                                        <td><label>
                                                            @if($query->personal_info->civil_status_id!=NULL)
                                                                {{$query->personal_info->civil_statuses->name}}
                                                            @endif
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Birthdate:</td>
                                                        <td><label>
                                                            @if(strtotime($query->personal_info->dob) !== false)
                                                                {{date('m/d/Y',strtotime($query->personal_info->dob))}}
                                                            @endif
                                                            </label>
                                                        </td>
                                                        <td>Birthplace:</td>
                                                        <td><label>
                                                            {{$query->personal_info->place_birth}}
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Born Country:</td>
                                                        <td><label>
                                                            @if($query->personal_info->country_id!=NULL)
                                                                {{$query->personal_info->country->name}}
                                                            @endif
                                                            </label>
                                                        </td>
                                                        <td>Citizenship:</td>
                                                        <td><label>
                                                                {{$query->personal_info->citizenship}}
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Religion:</td>
                                                        <td><label>
                                                            @if($query->personal_info->religion_id!=NULL)
                                                                {{$query->personal_info->religion->name}}
                                                            @endif
                                                            </label>
                                                        </td>
                                                        <td>NSTP Serial No.:</td>
                                                        <td><label>
                                                            @if($query->student_info)
                                                                {{$query->student_info->nstp_serial_no}}
                                                            @endif
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Department:</td>
                                                        <td><label>
                                                            @if($query->student_info)
                                                                {{$query->student_info->program->departments->name}} ({{$query->student_info->program->departments->shorten}})
                                                            @endif
                                                            </label>
                                                        </td>
                                                        <td>Program:</td>
                                                        <td><label>
                                                            @if($query->student_info)
                                                                {{$query->student_info->program->name}} ({{$query->student_info->program->shorten}})
                                                            @endif
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Curriculum:</td>
                                                        <td><label>
                                                            @if($query->student_info)
                                                                @if($query->student_info->curriculum_id!=NULL)
                                                                    {{$query->student_info->curriculum->year_from}}
                                                                @endif
                                                            @endif
                                                            </label>
                                                        </td>
                                                        <td>Grade Level:</td>
                                                        <td><label>
                                                            @if($query->student_info)
                                                                {{$query->student_info->grade_level->name}}
                                                            @endif
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab" style="min-height: 250px;">
                                        <div class="row">
                                            <div class="col-12">
                                                <button class="btn btn-info btn-info-scan btn-xs studentInfoEdit"
                                                    data-val="Contact" style="float:right">
                                                    <span class="fa fa-edit"> Edit</span>
                                                </button>
                                            </div>
                                            <div class="col-lg-12 table-responsive">
                                                <table class="table">
                                                    <tr>
                                                        <td style="width: 25%">Contact No. 1:</td>
                                                        <td style="width: 25%"><label>
                                                            +63-{{$query->personal_info->contact_no_official}}
                                                            </label>
                                                        </td>
                                                        <td style="width: 20%">Email 1:</td>
                                                        <td style="width: 30%"><label>
                                                            {{$query->personal_info->email_official}}
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Contact No. 2:</td>
                                                        <td><label>
                                                            +63-{{$query->personal_info->contact_no}}
                                                            </label>
                                                        </td>
                                                        <td>Email 2:</td>
                                                        <td><label>
                                                            {{$query->personal_info->email}}
                                                            <label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Telephone No:</td>
                                                        <td><label>
                                                            {{$query->personal_info->telephone_no}}
                                                            </label>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Residential Address:
                                                        </td>
                                                        <td colspan="3"><label>
                                                            {{$query->personal_info->res_lot}} {{$query->personal_info->res_street}} {{$query->personal_info->res_subd}}
                                                            @if($query->personal_info->res_brgy_id!=NULL)
                                                                {{$query->personal_info->res_brgy->name}}
                                                            @endif
                                                            @if($query->personal_info->res_municipality_id!=NULL)
                                                                {{$query->personal_info->res_city_muns->name}}
                                                            @endif
                                                            @if($query->personal_info->res_province_id!=NULL)
                                                                {{$query->personal_info->per_province->name}}
                                                            @endif
                                                            {{$query->personal_info->res_zip_code}}</label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Permanent Address:
                                                        </td>
                                                        <td colspan="3"><label>
                                                            {{$query->personal_info->per_lot}} {{$query->personal_info->per_street}} {{$query->personal_info->per_subd}}
                                                            @if($query->personal_info->per_brgy_id!=NULL)
                                                                {{$query->personal_info->per_brgy->name}}
                                                            @endif
                                                            @if($query->personal_info->per_municipality_id!=NULL)
                                                                {{$query->personal_info->per_city_muns->name}}
                                                            @endif
                                                            @if($query->personal_info->per_province_id!=NULL)
                                                                {{$query->personal_info->per_province->name}}
                                                            @endif
                                                            {{$query->personal_info->per_zip_code}}</label>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab" style="min-height: 250px;">
                                        <div class="row">
                                            <div class="col-12">
                                                <button class="btn btn-info btn-info-scan btn-xs studentInfoEdit"
                                                    data-val="Educ" style="float:right">
                                                    <span class="fa fa-edit"> Edit</span>
                                                </button>
                                            </div>
                                            <div class="col-lg-12 table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Level</th>
                                                            <th>School</th>
                                                            <th>Program</th>
                                                            <th>Period From</th>
                                                            <th>Period To</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(count($query->education)>0)
                                                            @php
                                                                $x = 1;
                                                            @endphp
                                                            @foreach($query->education as $row)
                                                                <tr>
                                                                    <td>{{$row->level->name}}</td>
                                                                    <td>{{$row->school->name}}</td>
                                                                    <td>
                                                                        @if($row->program_id!=NULL)
                                                                            {{$row->program->name}}
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if(strtotime($row->period_from) !== false)
                                                                            {{date('m/d/Y',strtotime($row->period_from))}}
                                                                        @else
                                                                            {{$row->period_from}}
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if(strtotime($row->period_to) !== false)
                                                                            {{date('m/d/Y',strtotime($row->period_to))}}
                                                                        @else
                                                                            {{$row->period_to}}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr id="educBgViewMore{{$x}}">
                                                                    <td></td>
                                                                    <td colspan="4">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                Units Earned: {{$row->units_earned}}
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                Year Graduated: {{$row->year_grad}}
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                Honors: {{$row->honors}}
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    $x++;
                                                                @endphp
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="tab-pane fade" id="custom-tabs-three-settings" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab" style="min-height: 250px;">
                                        <div class="row">
                                            <div class="col-12">
                                                <button class="btn btn-info btn-info-scan btn-xs studentInfoEdit"
                                                    data-val="Fam" style="float:right">
                                                    <span class="fa fa-edit"> Edit</span>
                                                </button>
                                            </div>
                                            <div class="col-8 col-sm-10">
                                                <div class="tab-content" id="vert-tabs-right-tabContent">
                                                @if(count($query->family)>0)
                                                    @php
                                                        $x = 0;
                                                    @endphp
                                                    @foreach($query->family as $row)
                                                        <div class="tab-pane fade @if($x==0) show active @endif"
                                                            id="{{$row->fam_relation->name}}" role="tabpanel"
                                                            aria-labelledby="{{$row->fam_relation->name}}-tab">
                                                            <div class="table-responsive"><br>
                                                                <table class="table">
                                                                    <tr>
                                                                        <td>Name:</td>
                                                                        <td colspan="3">
                                                                            <label>{{$row->lastname}},
                                                                                {{$row->firstname}}
                                                                                {{$row->extname}}
                                                                                {{$row->middlename}}</label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Birthdate:</td>
                                                                        <td><label>@if(strtotime($row->dob) !== false) {{date('F d, Y',strtotime($row->dob))}} @endif</label></td>
                                                                        <td>Contact:</td>
                                                                        <td><label>{{$row->contact_no}}</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Email:</td>
                                                                        <td><label>{{$row->email}}</label></td>
                                                                        <td>Occupation:</td>
                                                                        <td><label>{{$row->occupation}}</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Employer:</td>
                                                                        <td><label>{{$row->employer}}</label></td>
                                                                        <td>Employer Contact:</td>
                                                                        <td><label>{{$row->employer_contact}}</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Employer Address:</td>
                                                                        <td colspan="3"><label>{{$row->employer_address}}</label></td>
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
                                                @if(count($query->family)>0)
                                                    @php
                                                        $x = 0;
                                                    @endphp
                                                    @foreach($query->family as $row)
                                                        <a class="nav-link @if($x==0) active @endif"
                                                                id="{{$row->fam_relation->name}}-tab"
                                                                data-toggle="pill"
                                                                href="#{{$row->fam_relation->name}}"
                                                                role="tab"
                                                                aria-controls="{{$row->fam_relation->name}}"
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
                                </div>
                            </div>
                        </div>

                </div>
            </div>
            <div class="col-lg-12">
                <div class="card card-info card-outline">
                    <div class="card-body">
                        <button class="btn btn-primary btn-primary-scan" id="tor">
                            <span class="fa fa-graduation-cap"></span> TOR</button> &nbsp;
                        <button class="btn btn-info btn-info-scan" id="curriculum">
                            <span class="fa fa-list"></span> Curriculum</button>  &nbsp;
                        <button class="btn btn-primary btn-primary-scan" id="grades">
                                <span class="fa fa-star-half-o"></span> Grades</button>  &nbsp;
                        <button class="btn btn-info btn-info-scan" id="certification">
                                <span class="fa fa-check"></span> Certification</button>  &nbsp;
                        @if($program_level>=6) &nbsp;
                            <button class="btn btn-primary btn-primary-scan" id="shift">
                                <span class="fa fa-rotate-right"></span> Shift</button>
                        @endif
                        @if(!$query->student_info) &nbsp;
                            <button class="btn btn-success btn-success-scan" id="selectProgram">
                                <span class="fa fa-check"></span> Select Program First</button>
                        @endif
                        <table id="studentSchoolYearTable" class="table table-bordered table-fixed"
                                data-toggle="table"
                                data-search="true"
                                data-height="450"
                                data-buttons-class="primary"
                                data-show-export="true"
                                data-show-columns-toggle-all="true"
                                data-mobile-responsive="true"
                                data-pagination="false"
                                data-loading-template="loadingTemplate"
                                data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                            <thead>
                                <tr>
                                    <th data-field="f1" data-sortable="true" data-align="center">#</th>
                                    <th data-field="f2" data-sortable="true" data-align="center">School Year</th>
                                    <th data-field="f3" data-sortable="true" data-align="center">Level</th>
                                    <th data-field="f4" data-sortable="true" data-align="center">Program</th>
                                    <th data-field="f5" data-sortable="true" data-align="center">Grade Level</th>
                                    <th data-field="f6" data-sortable="true" data-align="center">No. of Course</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<script src="{{ asset('assets/js/rims/student/certification.js') }}"></script>
<script src="{{ asset('assets/js/rims/student/selectProgram.js') }}"></script>
<script src="{{ asset('assets/js/rims/student/gradesModal.js') }}"></script>

<!-- /.modal-content -->
