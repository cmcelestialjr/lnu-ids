
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
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 center"><br>
                                <img src="{{ asset('assets/images/icons/png/user.png') }}" class="profile-picture" alt="Student Image">
                            </div>
                            <div class="col-lg-8 table-responsive">
                                <table class="table">
                                    <tr>
                                        <td style="width: 10%">Lastname:</td>
                                        <td style="width: 20%"><label>{{$query->lastname}}</label></td>
                                        <td style="width: 10%">Firstname:</td>
                                        <td style="width: 20%"><label>{{$query->firstname}}</label></td>
                                    </tr>
                                    <tr>
                                        <td>Middlename:</td>
                                        <td><label>{{$query->middlename}}</label></td>
                                        <td>Extname:</td>
                                        <td><label>{{$query->extname}}</label></td>
                                    </tr>
                                    <tr>
                                        <td>ID No:</td>
                                        <td><label>{{$query->stud_id}}</label></td>
                                        <td>Birthdate:</td>
                                        <td><label>
                                            {{$query->personal_info->dob}}
                                        </label></td>
                                    </tr>
                                    <tr>
                                        <td>Contact:</td>
                                        <td><label>
                                            {{$query->personal_info->contact}}
                                            </label></td>
                                        <td>Email:</td>
                                        <td><label>
                                            {{$query->personal_info->email}}
                                            </label></td>
                                    </tr>
                                    <tr>
                                        <td>Sex:</td>
                                        <td><label>
                                            @if($query->personal_info->sex!=NULL)
                                            {{$query->personal_info->sexs->name}}
                                            @endif
                                            </label></td>
                                        <td>Department:</td>
                                        <td><label>
                                            @if($query->student_info)
                                                {{$query->student_info->program->departments->name}} ({{$query->student_info->program->departments->shorten}})
                                            @endif
                                            </label></td>
                                    </tr>
                                    <tr>
                                        <td>Program:</td>
                                        <td><label>
                                            @if($query->student_info)
                                                {{$query->student_info->program->name}} ({{$query->student_info->program->shorten}})
                                            @endif
                                            </label></td>
                                        <td>Grade Level:</td>
                                        <td><label>
                                            @if($query->student_info)
                                                {{$query->student_info->grade_level->name}}
                                            @endif
                                            </label></td>
                                    </tr>
                                </table>
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
