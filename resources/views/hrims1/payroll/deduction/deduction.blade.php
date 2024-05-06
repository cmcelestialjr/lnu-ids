@extends('layouts.header')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
              <div class="card card-primary card-tabs">
                  <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#list" role="tab" aria-selected="true">Deductions</a>
                      </li>
                      <li class="nav-item" id="groupLink">
                        <a class="nav-link" data-toggle="pill" href="#group" role="tab" aria-selected="false">Group</a>
                      </li>
                    </ul>
                  </div>
                  <div class="card-body">
                      <div class="tab-content">
                          <div class="tab-pane fade show active" id="list" role="tabpanel">
                              <div class="row">
                                  <div class="col-lg-12">
                                      <button class="btn btn-primary btn-primary-scan" name="new" style="float: right">
                                          <span class="fa fa-plus"></span> New Deduction
                                      </button><br>
                                      <br>
                                      <table id="listTable" class="table table-bordered table-fixed"
                                          data-toggle="table"
                                          data-search="true"
                                          data-height="480"
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
                                                  <th data-field="f3" data-sortable="true" data-align="center">Group</th>
                                                  <th data-field="f5" data-sortable="true" data-align="center">Employment Status</th>
                                                  <th data-field="f6" data-sortable="true" data-align="center">Payroll Type</th>
                                                  <th data-field="f4" data-sortable="true" data-align="center">Edit</th>
                                              </tr>
                                          </thead>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          <div class="tab-pane fade" id="group" role="tabpanel">
                              <div class="row">
                                  <div class="col-lg-12">
                                      <button class="btn btn-primary btn-primary-scan" name="new" style="float: right">
                                          <span class="fa fa-plus"></span> New Group
                                      </button><br>
                                      <br>
                                      <table id="groupTable" class="table table-bordered table-fixed"
                                          data-toggle="table"
                                          data-search="true"
                                          data-height="480"
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
                                                  <th data-field="f5" data-sortable="true" data-align="center">Employment Status</th>
                                                  <th data-field="f6" data-sortable="true" data-align="center">Payroll Type</th>
                                                  <th data-field="f3" data-sortable="true" data-align="center">Edit</th>
                                                  <th data-field="f4" data-sortable="true" data-align="center">View</th>
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
            <div class="card-footer">
            
            </div>
        </div>
    </div>
<!-- /.col-md-6 -->
</div>
<!-- /.row -->
@include('layouts.script')
<script src="{{ asset('assets/js/hrims/payroll/deduction/list.js') }}"></script>
<script src="{{ asset('assets/js/hrims/payroll/deduction/group.js') }}"></script>
@endsection