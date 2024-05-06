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
<div class="content">
  <!-- Container-fluid -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-4">
                  <label>Student</label>
                  <div id="ludongStudent">
                    <select class="form-control select2 ludongStudent" name="student">

                    </select>
                  </div>
                </div>
                <div class="col-lg-12">
                    <br>
                    <table id="studentTable" class="table table-bordered table-fixed"
                            data-toggle="table"
                            data-search="true"
                            data-height="1000"
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
                              <th data-field="f2" data-sortable="true" data-align="center">ID No.</th>                          
                              <th data-field="f3" data-sortable="true" data-align="center">Name</th> 
                              <th data-field="f4" data-sortable="true" data-align="center">Subjects</th>
                              <th data-field="f5" data-sortable="true" data-align="center">Pay Unit</th>
                              <th data-field="f6" data-sortable="true" data-align="center">Load Unit</th>
                              <th data-field="f7" data-sortable="true" data-align="center">Grade</th>
                            </tr>
                          </thead>
                    </table>
                </div>
              </div>
            </div>
            <div class="card-footer">
                
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
<script src="{{ asset('assets/js/search/ludong_student.js') }}"></script>
<script src="{{ asset('assets/js/rims/ludong/students.js') }}"></script>
@endsection