
<div class="modal-content" id="studentViewModal">
    <div class="modal-header">
        <h4 class="modal-title"></h4>
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
                            <div class="col-lg-8 table-responsive"><br>
                                <table class="table">
                                    <tr>
                                        <td style="width: 10%">Lastname:</td>
                                        <td style="width: 20%"><label>{{$query->info->lastname}}</label></td>
                                        <td style="width: 10%">Firstname:</td>
                                        <td style="width: 20%"><label>{{$query->info->firstname}}</label></td>
                                    </tr>
                                    <tr>
                                        <td>Middlename:</td>
                                        <td><label>{{$query->info->middlename}}</label></td>
                                        <td>Extname:</td>
                                        <td><label>{{$query->info->extname}}</label></td>
                                    </tr>
                                    <tr>
                                        <td>ID No:</td>
                                        <td><label>{{$query->id_no}}</label></td>
                                        <td>Birthdate:</td>
                                        <td><label>
                                            {{$query->info->personal_info->dob}}
                                        </label></td>
                                    </tr>
                                    <tr>
                                        <td>Contact:</td>
                                        <td><label>
                                            {{$query->info->personal_info->contact}}
                                            </label></td>
                                        <td>Email:</td>
                                        <td><label>
                                            {{$query->info->personal_info->email}}
                                            </label></td>
                                    </tr>
                                    <tr>
                                        <td>Sex:</td>
                                        <td><label>
                                            {{$query->info->personal_info->sex}}
                                            </label></td>
                                        <td>Department:</td>
                                        <td><label>
                                            {{$query->program->departments->name}} ({{$query->program->departments->shorten}})
                                            </label></td>
                                    </tr>
                                    <tr>
                                        <td>Program:</td>
                                        <td><label>
                                            {{$query->program->name}} ({{$query->program->shorten}})
                                            </label></td>
                                        <td>Grade Level:</td>
                                        <td><label>
                                            {{$query->grade_level->name}}
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
                            <span class="fa fa-list"></span> Curriculum</button>
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
<!-- /.modal-content -->
