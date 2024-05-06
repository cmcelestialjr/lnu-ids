@extends('layouts.header')
@section('content')
<div class="card card-primary card-tabs" id="enrollmentDiv">
  <div class="card-header p-0 pt-1">
    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#list" role="tab" aria-selected="true">List</a>
      </li>
      <li class="nav-item" id="advisementLink">
        <a class="nav-link" data-toggle="pill" href="#advisementTab" role="tab" aria-selected="false">Advised</a>
      </li>
    </ul>
  </div>                
    <div class="card-body">  
      <div class="tab-content">
        <div class="tab-pane fade show active" id="list" role="tabpanel">
          <div class="row">
            <div class="col-lg-4">
                  <label>School Year</label>
                  <select class="form-control select2" name="school_year">
                      @php
                          $x = 0;
                          $enrollment_from = '';
                          $enrollment_to = '';
                          $enrollment_extension = '';
                      @endphp
                      @foreach($school_year as $row)
                        @php
                          if($x==0){
                            $enrollment_from = $row->enrollment_from;
                            $enrollment_to = $row->enrollment_to;
                            $enrollment_extension = $row->enrollment_extension;
                          }
                        @endphp
                        <option value="{{$row->id}}">{{$row->year_from}} - {{$row->year_to}} ({{$row->grade_period->name}})</option>
                        @php
                          $x++;
                        @endphp
                      @endforeach
                  </select>
            </div>
              <div class="col-lg-4">
                <label>By</label>
                <select class="form-control select2" name="by">
                  <option value="date">By Date</option>
                  <option value="program">By Program</option>
                </select>
              </div>
              <div class="col-lg-4" id="dateDiv">
                <label>Date</label>
                <select class="form-control select2" name="date">
                  
                </select>
              </div>

              <div class="col-lg-12">
                <br>
                <div class="card card-info card-outline">
                  <div class="card-body">
                    @if($user_access->level_id==1 || $user_access->level_id==2 || $user_access->level_id==3)
                      @if($enrollment_from!='' && $enrollment_from<=date('Y-m-d') && ($enrollment_to>=date('Y-m-d')) || $enrollment_extension>=date('Y-m-d'))
                      <button class="btn btn-primary btn-primary-scan" name="enroll" style="float:right">
                        <span class="fa fa-plus-square"></span> Enroll
                      </button>
                      <br><br>
                      @endif
                    @endif
                    <div class="hide" id="enrollmentDivprogram">
                      <table id="enrollmentTableprogram" class="table table-bordered table-fixed"
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
                                  <th data-field="f1" data-sortable="true" data-align="center">#</th>
                                  <th data-field="f2" data-sortable="true" data-align="center">Department</th>
                                  <th data-field="f3" data-sortable="true" data-align="center">Program</th>
                                  <th data-field="f4" data-sortable="true" data-align="center">Code</th>
                                  <th data-field="f5" data-sortable="true" data-align="center">No of Enrollee</th>
                                  <th data-field="f6" data-sortable="true" data-align="center">View</th>
                              </tr>
                          </thead>
                      </table>
                    </div>
                    <div id="enrollmentDivdate">
                      <table id="enrollmentTabledate" class="table table-bordered table-fixed"
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
                                  <th data-field="f1" data-sortable="true" data-align="center">#</th>
                                  <th data-field="f2" data-sortable="true" data-align="center">ID NO</th>
                                  <th data-field="f3" data-sortable="true" data-align="center">Name</th>
                                  <th data-field="f4" data-sortable="true" data-align="center">Department</th>
                                  <th data-field="f5" data-sortable="true" data-align="center">Program</th>
                                  <th data-field="f6" data-sortable="true" data-align="center">Level</th>
                                  <th data-field="f7" data-sortable="true" data-align="center">Status</th>
                                  <th data-field="f8" data-sortable="true" data-align="center">Courses</th>
                                  <th data-field="f9" data-sortable="true" data-align="center">Option</th>
                              </tr>
                          </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>            
        <div class="tab-pane" id="advisementTab" role="tabpanel">
          <div class="row">
            <div class="col-lg-4">
              <label>Level</label>
              <select class="form-control select2">
                @foreach($program_level as $row)
                  <option value="{{$row->id}}">{{$row->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-lg-12">
              <table id="advisedTableTab" class="table table-bordered table-fixed"
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
                      <th data-field="f1" data-sortable="true" data-align="center">#</th>
                      <th data-field="f2" data-sortable="true" data-align="center">ID NO</th>
                      <th data-field="f3" data-sortable="true" data-align="center">Name</th>
                      <th data-field="f4" data-sortable="true" data-align="center">Department</th>
                      <th data-field="f5" data-sortable="true" data-align="center">Program</th>
                      <th data-field="f6" data-sortable="true" data-align="center">Level</th>
                      <th data-field="f7" data-sortable="true" data-align="center">Status</th>
                      <th data-field="f8" data-sortable="true" data-align="center">Courses</th>
                      <th data-field="f9" data-sortable="true" data-align="center">Option</th>
                    </tr>
                  </thead>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

@include('layouts.script')
<script src="{{ asset('assets/js/rims/enrollment/_function.js') }}"></script>
<script src="{{ asset('assets/js/rims/enrollment/view.js') }}"></script>
<script src="{{ asset('assets/js/rims/enrollment/modal.js') }}"></script>
<script src="{{ asset('assets/js/rims/enrollment/update.js') }}"></script>
@endsection