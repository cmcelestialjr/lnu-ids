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
                        <a class="nav-link active" data-toggle="pill" href="#list" role="tab" aria-selected="true">List</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" data-toggle="pill" href="#import" role="tab" aria-selected="true">Import</a>
                        </li>
                    </ul>
                  </div>
                  <div class="card-body">
                      <div class="tab-content">
                          <div class="tab-pane fade show active" id="list" role="tabpanel">
                              <div class="row">
                                  <div class="col-lg-4">
                                      <label>Year</label>
                                      <select class="form-control select2" name="year">
                                          @for($i=date('Y'); $i >= 2022; $i--) 
                                              <option value="{{$i}}">{{$i}}</option>
                                          @endfor
                                      </select>
                                  </div>
                                  <div class="col-lg-12">                                            
                                      <table id="listTable" class="table table-bordered table-fixed"
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
                                                  <th data-field="f2" data-sortable="true" data-align="center">Deduction Group</th>
                                                  <th data-field="f3" data-sortable="true" data-align="center">Year</th>                                                        
                                                  <th data-field="f4" data-sortable="true" data-align="center">Month</th>
                                                  <th data-field="f5" data-sortable="true" data-align="center">Import By</th>
                                                  <th data-field="f6" data-sortable="true" data-align="center">Import DateTime</th>
                                                  <th data-field="f7" data-sortable="true" data-align="center">View</th>
                                              </tr>
                                          </thead>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          <div class="tab-pane fade" id="import" role="tabpanel">
                              <div class="row">
                                  <div class="col-lg-12">
                                      <label>Import Billing</label><br>
                                      <form action="{{ url('/import/billing') }}" method="POST" enctype="multipart/form-data">
                                          @csrf
                                          <div class="row">
                                              <div class="col-lg-5">
                                                  <label>Payroll Type</label>
                                                  <select class="form-control select2" name="payroll_type">
                                                      @foreach($payroll_type as $row)
                                                          <option value="{{$row->id}}">{{$row->name}}</option>
                                                      @endforeach
                                                  </select>
                                                  <label>Deduction Group</label>
                                                  <select class="form-control select2" name="group">
                                                      @foreach($deduction_group as $row)
                                                          <option value="{{$row->id}}">{{$row->name}}</option>
                                                      @endforeach
                                                  </select>
                                                  <label>Year</label>
                                                  <select class="form-control select2" name="year">
                                                      @for($i=date('Y'); $i >= 2022; $i--) 
                                                          <option value="{{$i}}">{{$i}}</option>
                                                      @endfor
                                                  </select>
                                                  <label>Month</label>
                                                  <select class="form-control select2" name="month">
                                                      @for($i=1; $i <= 12; $i++) 
                                                          @php
                                                          $month = date('m', strtotime(date('Y').'-'.$i.'-01')); 
                                                          $month_name =  date('F', strtotime(date('Y').'-'.$i.'-01'));
                                                          @endphp
                                                          @if(date('m')==$month)
                                                              <option value="{{$month}}" selected>{{$month_name}}</option>
                                                          @else
                                                              <option value="{{$month}}">{{$month_name}}</option>
                                                          @endif
                                                      @endfor
                                                  </select>
                                              </div>
                                              <div class="col-lg-7"><br>
                                                  <div class="file-drop-area">
                                                      <button class="btn btn-primary btn-primary-scan">Choose file</button>
                                                      &nbsp; <span class="file-message">or drag and drop file here</span>
                                                      <input class="file-input" type="file" name="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                                  </div><br>
                                                  <button class="btn btn-primary btn-primary-scan" style="width: 100%;">Submit Import</button>
                                              </div>
                                          </div>
                                      </form>  
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
<script src="{{ asset('assets/js/hrims/payroll/billing/billing.js') }}"></script>
@endsection