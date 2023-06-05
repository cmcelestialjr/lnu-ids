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
<div class="content" id="subjectsDiv">
  <!-- Container-fluid -->
  <div class="container-fluid">
      <div class="row">
          <div class="col-lg-12">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" data-toggle="pill" href="#list" role="tab" aria-selected="true">List</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-toggle="pill" href="#search" role="tab" aria-selected="false">Search</a>
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
                            @foreach($school_year as $row)
                              <option value="{{$row->id}}">{{$row->year_from}}-{{$row->year_to}} ({{$row->grade_period->name}})</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-lg-4" id="gradeLevelDiv">
                            
                        </div>
                        <div class="col-lg-12">
                          <br>
                          <table id="subjectsTable" class="table table-bordered table-fixed"
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
                                <th data-field="f5" data-sortable="true" data-align="center">Course Code</th>
                                <th data-field="f2" data-sortable="true" data-align="center">Program</th>
                                <th data-field="f3" data-sortable="true" data-align="center">Curriculum</th>
                                <th data-field="f4" data-sortable="true" data-align="center">Section Code</th>                                
                                <th data-field="f6" data-sortable="true" data-align="center">Units</th>
                                <th data-field="f7" data-sortable="true" data-align="center">Schedule</th>
                                <th data-field="f8" data-sortable="true" data-align="center">Room</th>
                                <th data-field="f9" data-sortable="true" data-align="center">No. of Students</th>
                                <th data-field="f10" data-sortable="true" data-align="center">Status</th>
                                <th data-field="f11" data-sortable="true" data-align="center">View</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="search" role="tabpanel">
                      <div class="row">
                        <div class="col-lg-4">
                          
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
      <!-- /.col-md-6 -->
      </div>
  <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.Content -->
@include('layouts.script')
<script src="{{ asset('assets/js/fis/subjects/_function.js') }}"></script>
<script src="{{ asset('assets/js/fis/subjects/view.js') }}"></script>
<script src="{{ asset('assets/js/fis/subjects/modal.js') }}"></script>
<script src="{{ asset('assets/js/fis/subjects/new.js') }}"></script>
<script src="{{ asset('assets/js/fis/subjects/update.js') }}"></script>
<script src="{{ asset('assets/js/fis/subjects/delete.js') }}"></script>
@endsection