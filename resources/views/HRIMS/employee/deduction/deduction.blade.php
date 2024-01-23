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
<div class="content" id="deductionDiv">
  <!-- Container-fluid -->
  <div class="container-fluid">
      <div class="row">
          <div class="col-lg-12">
              <div class="card card-primary card-outline">
                  <div class="card-body">
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                          <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" data-toggle="pill" href="#list" role="tab" aria-selected="true">List</a>
                            </li>
                          </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="list" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label>Payroll Type</label>
                                            <select class="form-control select2" name="payroll_type">
                                                @foreach($payroll_type as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Employment Status</label>
                                            <div id="emp_statDiv">
                                                <select class="form-control select2" name="emp_stat[]" multiple>
                                                    @foreach($emp_stat as $row)
                                                        <option value="{{$row->id}}" data-g="{{$row->gov}}">{{$row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Fund Source</label>
                                            <div id="fund_sourceDiv">
                                                <select class="form-control select2" name="fund_source[]" multiple>
                                                    @foreach($fund_source as $row)
                                                        <option value="{{$row->id}}">{{$row->name}} ({{$row->code}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <label>Status</label>
                                            <select class="form-control select2" name="status">
                                                <option value="1">Active</option>
                                                <option value="2">InActive</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-1"><br>
                                            <button class="btn btn-info btn-info-scan" name="submit">
                                                <span class="fa fa-check"></span>
                                            </button>
                                        </div>
                                        <div class="col-lg-12"><br>
                                            <table id="listTable" class="table table-bordered table-fixed"
                                                data-toggle="table"
                                                data-search="true"
                                                data-height="600"
                                                data-buttons-class="primary"
                                                data-show-export="true"
                                                data-show-columns-toggle-all="true"
                                                data-mobile-responsive="true"
                                                data-pagination="true"
                                                data-page-size="5"
                                                data-page-list="[5, 50, 100, All]"
                                                data-loading-template="loadingTemplate"
                                                data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                                                <thead>
                                                    <tr>
                                                        <th data-field="f1" data-sortable="true" data-align="center">#</th>
                                                        <th data-field="f2" data-sortable="true" data-align="center">Name</th>
                                                        <th data-field="f3" data-sortable="true" data-align="center">Position</th>
                                                        <th data-field="f4" data-sortable="true" data-align="center">Salary</th>
                                                        <th data-field="f5" data-sortable="true" data-align="center">Deduction</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
<script src="{{ asset('assets/js/hrims/employee/deduction/deduction.js') }}"></script>
@endsection
