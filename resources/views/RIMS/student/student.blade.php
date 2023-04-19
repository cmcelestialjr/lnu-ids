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
<div class="content" id="studentDiv">
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
                <a class="nav-link" data-toggle="pill" href="#new" role="tab" aria-selected="false">Search</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane fade show active" id="list" role="tabpanel">
                <div class="row">
                  <div class="col-lg-4">
                    <label>Option</label>
                    <select class="form-control select2" name="option">
                        <option value="enrolled">Enrolled</option>
                        <option value="unenrolled">Unenrolled</option>
                        <option value="Graduated">Graduated</option>
                        <option value="Graduating">Graduating</option>
                    </select>
                  </div>
                  <div class="col-lg-4">
                    <label>Level</label>
                    <select class="form-control select2" name="level[]" multiple>
                      @foreach($program_level as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-4" id="school_year">
                    <label>School Year</label>
                    <select class="form-control select2" name="school_year">
                      @foreach($school_year as $row)
                        <option value="{{$row->id}}">{{$row->year_from}}-{{$row->year_to}} ({{$row->grade_period->name}})</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-4 hide" id="date_graduate">
                    <label>Date</label>
                    <select class="form-control select2" name="date_graduate">
                      @foreach($date_graduate as $row)
                        @if($row->date_graduate!=NULL)
                          <option value="{{$row->date_graduate}}">{{date('F d, Y',strtotime($row->date_graduate))}}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-12">
                    <br>
                    <table id="studentTable" class="table table-bordered table-fixed"
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
                          <th data-field="f2" data-sortable="true" data-align="center">Name</th>
                          <th data-field="f3" data-sortable="true" data-align="center">ID No.</th>
                          <th data-field="f4" data-sortable="true" data-align="center">Program Level</th>
                          <th data-field="f5" data-sortable="true" data-align="center">Program</th>
                          <th data-field="f6" data-sortable="true" data-align="center">Level</th>
                          <th data-field="f7" data-sortable="true" data-align="center">View</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="new" role="tabpanel">
                <div class="row">
                  <div class="col-lg-4">
                    
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
<script src="{{ asset('assets/js/rims/student/_function.js') }}"></script>
<script src="{{ asset('assets/js/rims/student/view.js') }}"></script>
<script src="{{ asset('assets/js/rims/student/modal.js') }}"></script>
<script src="{{ asset('assets/js/rims/student/new.js') }}"></script>
<script src="{{ asset('assets/js/rims/student/update.js') }}"></script>
<script src="{{ asset('assets/js/rims/student/delete.js') }}"></script>
@endsection