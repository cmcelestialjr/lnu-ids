@extends('layouts.header')
@section('content')
<div class="row" id="payrollView">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
              <div class="row">
                  <div class="col-lg-3">
                      <label>Payroll Type</label>
                      <select class="form-control select2" name="payroll_type">
                          <option value="All">All</option>
                          @foreach($payroll_type as $row)
                              <option value="{{$row->id}}">{{$row->name}}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="col-lg-3">
                      <label>By</label>
                      <select class="form-control select2" name="by">
                          <option value="year">By Year</option>
                          <option value="month" selected>By Month</option>
                      </select>
                  </div>
                  <div class="col-lg-3">
                      <label>Year</label>
                      <select class="form-control select2" name="year">
                          @for($i=date('Y'); $i >= 2022; $i--)
                              <option value="{{$i}}">{{$i}}</option>
                          @endfor
                      </select>
                  </div>
                  <div class="col-lg-3" id="monthDiv">
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
                  <div class="col-lg-3">
                      <label>Type</label>
                      <select class="form-control select2" name="type">
                          <option value="generate">Generated Payroll</option>
                          <option value="partial">Partial Payroll</option>
                      </select>
                  </div>
                  <div class="col-lg-1"><br>
                      <button class="btn btn-info btn-info-scan" name="submit">
                          <span class="fa fa-check"></span>
                      </button>
                  </div>
                  <div class="col-lg-12">

                      <br>
                      <table id="listTable" class="table table-bordered table-fixed"
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
                                  <th data-field="f2" data-sortable="true" data-align="center">Payroll ID</th>
                                  <th data-field="f3" data-sortable="true" data-align="center">Et Al</th>
                                  <th data-field="f4" data-sortable="true" data-align="center">Type</th>
                                  <th data-field="f5" data-sortable="true" data-align="center">Amount</th>
                                  {{-- <th data-field="f6" data-sortable="true" data-align="center">Bank</th> --}}
                                  @if($user_access->level_id==1 || $user_access->level_id==2 || $user_access->level_id==3)
                                    <th data-field="f7" data-sortable="true" data-align="center">Delete</th>
                                  @endif
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
@include('layouts.script')
<script src="{{ asset('assets/js/hrims/payroll/view/view.js') }}"></script>
@endsection
