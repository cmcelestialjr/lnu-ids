@extends('layouts.header')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">{{$system_selected}}</a></li>
            <li class="breadcrumb-item active">{{mb_strtoupper($nav_selected)}}</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->


<!-- Content -->
<div class="content" id="schoolYearDiv">
  <!-- Container-fluid -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card card-primary card-tabs">
          <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="schoolYearList" data-toggle="pill" href="#list" role="tab" aria-selected="true">List</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#new" role="tab" aria-selected="false">New</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane fade show active" id="list" role="tabpanel">
                <div class="row">
                  <div class="col-lg-12">
                    <table id="viewTable" class="table table-bordered table-fixed"
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
                          <th data-field="f2" data-sortable="true" data-align="center">School Year</th>
                          <th data-field="f3" data-sortable="true" data-align="center">School Period</th>
                          <th data-field="f4" data-sortable="true" data-align="center">School Duration</th>
                          <th data-field="f5" data-sortable="true" data-align="center">School Enrollment</th>
                          <th data-field="f6" data-sortable="true" data-align="center">School Add/Dropping</th>
                          <th data-field="f7" data-sortable="true" data-align="center">Programs</th>
                          @if($user_access->level_id==1 || $user_access->level_id==2)
                          <th data-field="f8" data-sortable="true" data-align="center">Edit</th>
                          @endif
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="new" role="tabpanel">
                <div class="row">
                                    
                  <div class="col-lg-4">
                    <label>School Year</label>
                    <div class="input-group date">
                      <div class="input-group-append">
                        <div class="input-group-text">From</div>
                      </div>
                      <input type="text" class="form-control yearpicker" name="year_from" value="{{date('Y')}}">
                      <div class="input-group-append">
                        <div class="input-group-text">To</div>
                      </div>
                      <input type="text" class="form-control yearpicker" name="year_to" value="{{date('Y')+1}}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label>School Period</label>
                    <select class="form-control select2" name="grade_period">
                      @foreach($grade_period as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-12">
                  </div>
                  <div class="col-lg-4">
                    <label>School Duration (Date)</label>
                    <div class="input-group date">
                      <div class="input-group-append">
                        <div class="input-group-text"><span class="fa fa-calendar"></span></div>
                      </div>
                      <input type="text" class="form-control date-range" name="date_duration">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label>Extension</label>
                    <input type="text" class="form-control datepicker" name="date_extension" value="{{date('Y-m-d')}}">
                  </div>
                  <div class="col-lg-12">
                  </div>
                  <div class="col-lg-4">
                    <label>Enrollment Duration</label>
                    <div class="input-group date">
                      <div class="input-group-append">
                        <div class="input-group-text"><span class="fa fa-calendar"></span></div>
                      </div>
                      <input type="text" class="form-control date-range" name="enrollment_duration">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label>Extension</label>
                    <input type="text" class="form-control datepicker" name="enrollment_extension" value="{{date('Y-m-d')}}">
                  </div>
                  <div class="col-lg-12">
                  </div>
                  <div class="col-lg-4">
                    <label>Add/Dropping Duration</label>
                    <div class="input-group date">
                      <div class="input-group-append">
                        <div class="input-group-text"><span class="fa fa-calendar"></span></div>
                      </div>
                      <input type="text" class="form-control date-range" name="add_dropping_duration" value="{{date('Y-m-d')}}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label>Extension</label>
                    <input type="text" class="form-control datepicker" name="add_dropping_extension" value="{{date('Y-m-d')}}">
                  </div>
                  <div class="col-lg-12">
                    <br>
                    <button class="btn btn-primary btn-primary-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.Content -->

@include('layouts.script')
<script src="{{ asset('assets/js/rims/school_year.js') }}"></script>
@endsection