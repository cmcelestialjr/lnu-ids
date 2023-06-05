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
<div class="content" id="employeeDiv">
  <!-- Container-fluid -->
  <div class="container-fluid">
      <div class="row">
          <div class="col-lg-12">
              <div class="card card-primary card-outline">
                  <div class="card-header">
                    {{-- <div class="alert alert-info" style="padding-top: 20px;padding-bottom: 20px;">
                      <div class="row">
                        <div class="col-md-6">
                          Total: <b>
                            <button class="btn btn-primary view-staff-status alert-blue" id="btn-all" data-id="all" style="border-radius: 20%; padding: 5px;">
                              gsa
                            </button></b>
                          &nbsp; / &nbsp; Total Active: <b>
                            <button class="btn btn-success alert-green view-staff-status" id="btn-active" data-id="1" style="border-radius: 20%; padding: 5px;">
                              gsa
                            </button></b>
                        </div>
                      </div>
                    </div> --}}
                    <div class="row">
                      <div class="col-lg-2">  
                        <select class="form-control select2" name="option">
                          <option value="all">All</option>
                          <option value="2">Personnel</option>
                          <option value="3">Faculty</option>
                        </select>
                      </div>
                      <div class="col-lg-9 table-responsive">
                        <div class="btn-group btn-group-md">
                          <button class="btn btn-info btn-info-scan" data-id="all">ALL <span class="alert-blue-g" id="num_all"></span></button>
                          <button class="btn btn-info btn-info-scan" data-id="1">Permanent <span class="alert-green-g" id="num_1"></span></button>
                          <button class="btn btn-info btn-info-scan" data-id="3">Temporary <span class="alert-green-g" id="num_2"></span></button>
                          <button class="btn btn-info btn-info-scan" data-id="2">Casual <span class="alert-green-g" id="num_3"></span></button>	
                          <button class="btn btn-info btn-info-scan" data-id="4">Job Order <span class="alert-green-g" id="num_4"></span></button>
                          <button class="btn btn-info btn-info-scan" data-id="5">Part Time <span class="alert-green-g" id="num_5"></span></button>
                          <button class="btn btn-info btn-info-scan" data-id="sep">Separated <span class="alert-danger-g" id="num_sep"></span></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <br>
                    <table id="employeeTable" class="table table-bordered table-fixed"
                        data-toggle="table"
                        data-search="true"
                        data-height="800"
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
                          <th data-field="f2" data-sortable="true" data-align="center">Employee ID</th>
                          <th data-field="f3" data-sortable="true" data-align="center">Name</th>
                          <th data-field="f4" data-sortable="true" data-align="center">Position</th>
                          <th data-field="f5" data-sortable="true" data-align="center">Salary</th>                                
                          <th data-field="f6" data-sortable="true" data-align="center">Status</th>
                          <th data-field="f7" data-sortable="true" data-align="center">Type</th>
                          <th data-field="f8" data-sortable="true" data-align="center">View</th>
                        </tr>
                      </thead>
                    </table>
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
<script src="{{ asset('assets/js/hrims/employee/employee.js') }}"></script>
<script src="{{ asset('assets/js/hrims/employee/employee_view.js') }}"></script>
<script src="{{ asset('assets/js/hrims/employee/information/_information.js') }}"></script>
<script src="{{ asset('assets/js/search/position.js') }}"></script>
<script src="{{ asset('assets/js/search/designation.js') }}"></script>
@endsection