@extends('layouts.header')
@section('content')
<div class="row" id="generateDiv">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
              <div class="card card-primary card-tabs">
                  <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#list" role="tab" aria-selected="true">Generate</a>
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
                                  <div class="col-lg-3">
                                    <label>Fund Service</label>
                                    <div id="fund_serviceDiv">
                                        <select class="form-control select2" name="fund_service[]" multiple>
                                            @foreach($fund_service as $row)
                                                <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                  <div class="col-lg-3">
                                      <label>Year</label>
                                      <select class="form-control select2" name="year">
                                          @for($i=date('Y'); $i >= 2022; $i--) 
                                              <option value="{{$i}}">{{$i}}</option>
                                          @endfor
                                      </select>
                                  </div>
                                  <div class="col-lg-3" id="monthSingleDiv">
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
                                  <div class="col-lg-3 hide" id="monthMultipleDiv">
                                      <label>Month</label>
                                      <div id="monthsDiv">
                                          <select class="form-control select2" name="months[]" multiple>
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
                                  </div>
                                  <div class="col-lg-3 hide" id="unclaimedDiv">
                                      <label>Unclaimed from last Year</label>
                                      <select class="form-control select2" name="unclaimeds[]" multiple>
                                          @for($i=1; $i <= 12; $i++)
                                              @php
                                              $month = date('m', strtotime(date('Y').'-'.$i.'-01')); 
                                              $month_name =  date('F', strtotime(date('Y').'-'.$i.'-01'));
                                              @endphp
                                              <option value="{{$month}}">{{$month_name}}</option>
                                          @endfor
                                      </select>
                                  </div>
                                  <div class="col-lg-3" id="optionDiv">
                                      <label>Option</label>
                                      <select class="form-control select2" name="option">
                                          @foreach($payroll_option as $row)
                                              <option value="{{$row->id}}">{{$row->name}}</option>
                                          @endforeach
                                      </select>
                                  </div>
                                  <div class="col-lg-3 hide" id="durationDiv">
                                      <label>Duration</label>
                                      <select class="form-control select2" name="duration">
                                          @foreach($payroll_duration as $row)
                                              <option value="{{$row->id}}">{{$row->name}}</option>
                                          @endforeach                                                
                                      </select>
                                  </div>                                        
                                  <div class="col-lg-3 hide" id="optionSelectDiv">
                                      <div class="row">
                                          <div class="col-lg-6">
                                              <label>Day From</label>
                                              <input type="number" class="form-control" name="day_from" value="0">
                                          </div>
                                          <div class="col-lg-6">
                                              <label>Day To</label>
                                              <input type="number" class="form-control" name="day_to" value="0">
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-lg-3">
                                      <label>Status</label>
                                      <select class="form-control select2" name="status">
                                          <option value="1">Active</option>
                                          <option value="2">InActive</option>
                                      </select>
                                  </div>
                                  <div class="col-lg-3" id="include_peraDiv">
                                      <label>Include PERA?</label>
                                      <select class="form-control select2" name="include_pera">
                                          <option value="Yes">Yes</option>
                                          <option value="No" selected>No</option>
                                      </select>
                                  </div>
                                  <div class="col-lg-3">
                                    <label>Payment Option</label>
                                    <select class="form-control select2" name="account_title">
                                        @foreach($account_titles as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                  <div class="col-lg-12"><br>
                                      <button class="btn btn-info btn-info-scan" name="submit" style="width:100%">
                                          <span class="fa fa-check"></span> Submit
                                      </button>
                                  </div>
                                  <div class="col-lg-12" id="listTableDiv">
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
                                                <th data-field="f2" data-align="center">
                                                    <input type="checkbox" class="form-control" id="all" checked>
                                                </th>
                                                <th data-field="f3" data-sortable="true" data-align="center">Name</th>
                                                <th data-field="f4" data-sortable="true" data-align="center">Position</th>            
                                                <th data-field="f5" data-sortable="true" data-align="center">Salary</th>
                                                <th data-field="f6" data-sortable="true" data-align="center">Gross</th>
                                                <th data-field="f7" data-sortable="true" data-align="center">Deduction</th>
                                                <th data-field="f8" data-sortable="true" data-align="center">NetPay</th>
                                            </tr>
                                        </thead>
                                    </table>
                                  </div>
                                  <div class="col-lg-12">
                                      
                                      <br>
                                      {{-- <button class="btn btn-primary btn-primary-scan generate" value="generate">
                                          <span class="fa fa-check"></span> Generate Payroll
                                      </button> &nbsp; &nbsp; --}}
                                      <button class="btn btn-info btn-info-scan generate" value="partial">
                                          <span class="fa fa-check"></span> Partial Payroll
                                      </button>
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
<script src="{{ asset('assets/js/hrims/payroll/generate/generate.js') }}"></script>
@endsection