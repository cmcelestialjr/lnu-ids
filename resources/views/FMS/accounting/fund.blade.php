@extends('layouts.header')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
            </div>
            <div class="card-body">
              <div class="card card-primary card-tabs">
                  <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#cluster" role="tab" aria-selected="true">Fund Cluster</a>
                      </li>
                      <li class="nav-item" id="sourceLink">
                        <a class="nav-link" data-toggle="pill" href="#source" role="tab" aria-selected="false">Fund Category</a>
                      </li>
                      <li class="nav-item" id="servicesLink">
                          <a class="nav-link" data-toggle="pill" href="#services" role="tab" aria-selected="false">Fund Services</a>
                        </li>
                      {{-- <li class="nav-item" id="financingLink">
                          <a class="nav-link" data-toggle="pill" href="#financing" role="tab" aria-selected="false">Fund Finance</a>
                        </li> --}}
                    </ul>
                  </div>
                  <div class="card-body">
                      <div class="tab-content">
                          <div class="tab-pane fade show active" id="cluster" role="tabpanel">
                              <div class="row">
                                  <div class="col-lg-12">
                                      <button class="btn btn-primary btn-primary-scan" name="new" style="float: right">
                                          <span class="fa fa-plus"></span> New Fund Cluster
                                      </button><br>
                                      <br>
                                      <table id="clusterTable" class="table table-bordered table-fixed"
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
                                                  <th data-field="f3" data-sortable="true" data-align="center">Shorten</th>
                                                  <th data-field="f4" data-sortable="true" data-align="center">Code</th>
                                                  <th data-field="f5" data-sortable="true" data-align="center">View</th>
                                              </tr>
                                          </thead>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          <div class="tab-pane fade" id="source" role="tabpanel">
                              <div class="row">
                                  <div class="col-lg-12">
                                      <button class="btn btn-primary btn-primary-scan" name="new" style="float: right">
                                          <span class="fa fa-plus"></span> New Fund Category
                                      </button><br>
                                      <br>
                                      <table id="sourceTable" class="table table-bordered table-fixed"
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
                                                  <th data-field="f3" data-sortable="true" data-align="center">Shorten</th>
                                                  <th data-field="f4" data-sortable="true" data-align="center">Code</th>
                                                  <th data-field="f5" data-sortable="true" data-align="center">UACS</th>
                                                  <th data-field="f6" data-sortable="true" data-align="center">View</th>
                                              </tr>
                                          </thead>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          <div class="tab-pane fade" id="services" role="tabpanel">
                              <div class="row">
                                  <div class="col-lg-12">
                                      <button class="btn btn-primary btn-primary-scan" name="new" style="float: right">
                                          <span class="fa fa-plus"></span> New Fund Services
                                      </button><br>
                                      <br>
                                      <table id="servicesTable" class="table table-bordered table-fixed"
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
                                                  <th data-field="f3" data-sortable="true" data-align="center">Shorten</th>
                                                  <th data-field="f4" data-sortable="true" data-align="center">View</th>
                                              </tr>
                                          </thead>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          {{-- <div class="tab-pane fade" id="financing" role="tabpanel">
                              <div class="row">
                                  <div class="col-lg-12">
                                      <button class="btn btn-primary btn-primary-scan" name="new" style="float: right">
                                          <span class="fa fa-plus"></span> New Fund Finance
                                      </button><br>
                                      <br>
                                      <table id="financingTable" class="table table-bordered table-fixed"
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
                                                  <th data-field="f3" data-sortable="true" data-align="center">View</th>
                                              </tr>
                                          </thead>
                                      </table>
                                  </div>
                              </div>
                          </div> --}}
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
<script src="{{ asset('assets/js/fms/accounting/fund.js') }}"></script>
@endsection