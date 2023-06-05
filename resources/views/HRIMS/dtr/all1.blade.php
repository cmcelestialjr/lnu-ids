
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
                  <div class="card-body">
                    <div class="card card-primary card-tabs">
                      <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="schoolYearList" data-toggle="pill" href="#list" role="tab" aria-selected="true">All</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link holidayLi" data-toggle="pill" href="#holiday" role="tab" aria-selected="false">Holidays</a>
                          </li>
                        </ul>
                      </div>
                      <div class="card-body">
                        <div class="tab-content">
                          <div class="tab-pane fade show active" id="list" role="tabpanel">
                            <div class="row" id="all_div">
                              <div class="col-lg-2">
                                <label>Year</label>
                                <select class="form-control select2" name="year">
                                  @for ($i = date('Y'); $i >= 2023; $i--)
                                    <option value="{{$i}}">{{$i}}</option>
                                  @endfor
                                </select>
                              </div>
                              <div class="col-lg-2">
                                <label>Month</label>
                                <select class="form-control select2" name="month">
                                    @for($i=1;$i<=12;$i++)
                                      @if(date('m')==$i)
                                        <option value="{{$i}}" selected>{{date('F', strtotime(date('Y').'-'.$i.'-01'))}}</option>
                                      @else
                                        <option value="{{$i}}">{{date('F', strtotime(date('Y').'-'.$i.'-01'))}}</option>
                                      @endif
                                    @endfor
                                </select>
                              </div>
                              <div class="col-lg-2">
                                <label>Range</label>
                                <select class="form-control select2" name="range">
                                  <option value="1">Whole Month</option>
                                  <option value="2">Half Month (1-15)</option>
                                </select>
                              </div>
                              <div class="col-lg-2">
                                <label>Option</label>
                                <select class="form-control select2" name="option1">
                                  <option value="with">With Data</option>
                                  <option value="Submitted">Submitted</option>
                                  <option value="not">Not yet Submitted</option>
                                </select>
                              </div>
                              <div class="col-lg-1">
                                <br>
                                <button type="button" class="btn btn-info btn-info-scan" name="submit">
                                  <span class="fa fa-check"></span></button>
                              </div>
                              <div class="col-lg-12"><br>
                                <div class="card card-info card-outline">
                                  <div class="card-body row">
                                      <div class="col-lg-12 table-responsive">
                                        <table id="employeeTable" class="table table-bordered table-fixed"
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
                                              <th data-field="f2" data-sortable="true" data-align="center">Employee ID</th>
                                              <th data-field="f3" data-sortable="true" data-align="center">Name</th>
                                              <th data-field="f4" data-sortable="true" data-align="center">Position</th>
                                              <th data-field="f5" data-sortable="true" data-align="center">Salary</th>
                                              <th data-field="f6" data-sortable="true" data-align="center">Status</th>
                                              <th data-field="f7" data-sortable="true" data-align="center">Type</th>
                                              <th data-field="f8" data-sortable="true" data-align="center">Date Submitted</th>
                                              <th data-field="f9" data-sortable="true" data-align="center">View</th>
                                            </tr>
                                          </thead>
                                        </table>
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="tab-pane fade" id="holiday" role="tabpanel">
                            <div class="row" id="holidayDiv"> 
                              <div class="col-lg-2">
                                <label>Year</label>
                                <select class="form-control select2" name="year">
                                  @for ($i = date('Y'); $i >= 2023; $i--)
                                    <option value="{{$i}}">{{$i}}</option>
                                  @endfor
                                </select>
                              </div>
                              <div class="col-lg-10">
                                <button class="btn btn-info btn-info-scan" name="new" style="float:right;">
                                  <span class="fa fa-plus"></span> New Holiday</button>
                              </div>
                              <div class="col-lg-12"><br>
                                <div class="card card-info card-outline">
                                  <div class="card-body row">
                                      <div class="col-lg-12 table-responsive">
                                        <table id="holidayTable" class="table table-bordered table-fixed"
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
                                              <th data-field="f3" data-sortable="true" data-align="center">Date</th>
                                              <th data-field="f4" data-sortable="true" data-align="center">Type</th>
                                              <th data-field="f5" data-sortable="true" data-align="center">Edit</th>
                                            </tr>
                                          </thead>
                                        </table>
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
<script src="{{ asset('assets/js/hrims/dtr/all.js') }}"></script>
<script src="{{ asset('assets/js/hrims/dtr/holiday.js') }}"></script>
@endsection