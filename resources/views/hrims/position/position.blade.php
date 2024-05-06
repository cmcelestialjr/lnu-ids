@extends('layouts.header')
@section('content')
<div class="row" id="positionDiv">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
            </div>
            <div class="card-body">
              <div class="card card-primary card-tabs">
                  <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#position" role="tab" aria-selected="true">Position</a>
                      </li>
                      <li class="nav-item" id="designationLink">
                        <a class="nav-link" data-toggle="pill" href="#designation" role="tab" aria-selected="false">Designation</a>
                      </li>
                    </ul>
                  </div>
                  <div class="card-body">
                      <div class="tab-content">
                          <div class="tab-pane fade show active" id="position" role="tabpanel">
                              <div class="row">
                                  <div class="col-lg-3">
                                      <label>Type</label>
                                      <select class="form-control select2" name="type">
                                          <option value="All">All</option>
                                          @foreach($position_type as $row)
                                          <option value="{{$row->id}}">{{$row->name}}</option>
                                          @endforeach
                                      </select>
                                  </div>
                                  <div class="col-lg-3">
                                      <label>Status</label>
                                      <select class="form-control select2" name="status">
                                          @foreach($position_status as $row)
                                          <option value="{{$row->id}}">{{$row->name}}</option>
                                          @endforeach
                                      </select>
                                  </div>
                                  <div class="col-lg-12">
                                      <button class="btn btn-primary btn-primary-scan" name="new" style="float: right">
                                          <span class="fa fa-plus"></span> New Position
                                      </button><br>
                                      <br>
                                      <table id="positionTable" class="table table-bordered table-fixed"
                                              data-toggle="table"
                                              data-search="true"
                                              data-height="470"
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
                                                  <th data-field="f2" data-sortable="true" data-align="center">Item No.</th>
                                                  <th data-field="f3" data-sortable="true" data-align="center">Position</th>
                                                  <th data-field="f4" data-sortable="true" data-align="center">Shorten</th>
                                                  <th data-field="f5" data-sortable="true" data-align="center">SG</th>
                                                  <th data-field="f6" data-sortable="true" data-align="center">Level</th>
                                                  <th data-field="f7" data-sortable="true" data-align="center">Type</th>                                
                                                  <th data-field="f8" data-sortable="true" data-align="center">Emp Status</th>
                                                  <th data-field="f9" data-sortable="true" data-align="center">Fund</th>
                                                  <th data-field="f10" data-sortable="true" data-align="center">Role</th>
                                                  <th data-field="f11" data-sortable="true" data-align="center">Designation</th>
                                                  <th data-field="f12" data-sortable="true" data-align="center">Date Created</th>
                                                  <th data-field="f13" data-sortable="true" data-align="center">Edit</th>
                                                  <th data-field="f14" data-sortable="true" data-align="center">View</th>
                                              </tr>
                                          </thead>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          <div class="tab-pane fade" id="designation" role="tabpanel">
                              <div class="row">                                
                                <div class="col-lg-12" id="designationDiv">
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
<script src="{{ asset('assets/js/search/designation.js') }}"></script>
<script src="{{ asset('assets/js/hrims/position/position.js') }}"></script>
<script src="{{ asset('assets/js/hrims/designation/view.js') }}"></script>
@endsection