
<div class="modal-content" id="deductionModalDiv">
    <div class="modal-header">
        <h4><span class="fa fa-edit"></span> {{$query->employee->lastname}}, {{$query->employee->firstname}} {{$query->employee->extname}} {{$query->employee->middlename}}</h4>
        <input type="hidden" name="id" value="{{$query->id}}">
        <input type="hidden" name="li" value="deduction">
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2">
                        <label>Salary</label>
                        <select class="form-control select2-default" name="salary">
                            @foreach($salaries as $salary)
                                @if($salary==$query->salary)
                                    <option value="{{$salary}}" selected>{{$salary}}</option>
                                @else
                                    <option value="{{$salary}}">{{$salary}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    @if($query->payroll->payroll_type_id<=2 && $query->emp_stat_id!=5)
                        <div class="col-lg-2">
                            <label>Days Accu</label>
                            <input type="number" class="form-control lwopInput" data-n="day_accu" name="day_accu" value="{{$query->day_accu}}">
                        </div>
                    @endif
                    @if($query->payroll->payroll_type_id>2)
                        <div class="col-lg-2">
                            <label>W/ Salary Amount</label>
                            <input type="text" class="form-control" name="amount_base" value="{{$query->amount_base}}" readonly>
                        </div>
                        <div class="col-lg-2">
                            <label>Column Amount1</label>
                            <input type="text" class="form-control" name="column_amount" value="{{$query->column_amount}}" readonly>
                        </div>
                        <div class="col-lg-2">
                            <label>Column Amount2</label>
                            <input type="text" class="form-control" name="column_amount2" value="{{$query->column_amount2}}" readonly>
                        </div>
                    @else
                    <input type="hidden" name="amount_base" value="{{$query->amount_base}}" readonly>
                    <input type="hidden" name="column_amount" value="{{$query->column_amount}}" readonly>
                    <input type="hidden" name="column_amount2" value="{{$query->column_amount2}}" readonly>
                    @endif
                    <div class="col-lg-2">
                        <label>Earned</label>
                        <input type="text" class="form-control" name="earned" value="{{$query->earned}}" readonly>
                    </div>
                    <div class="col-lg-2">
                        <label>Allowance</label>
                        <input type="text" class="form-control" name="allowance" value="{{$allowance->sum('amount')}}" readonly>
                    </div>
                    <div class="col-lg-2">
                        <label>Deduction</label>
                        <input type="text" class="form-control" name="deductions" value="{{$deductions->sum('amount')+$query->lwop}}" readonly>
                    </div>
                    <div class="col-lg-2">
                        <label>Netpay</label>
                        <input type="text" class="form-control" name="netpay" value="{{$query->netpay}}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
              <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item" id="deductionLi">
                    <a class="nav-link active" data-toggle="pill" href="#deduction" role="tab" aria-selected="true">Deduction</a>
                </li>
                <li class="nav-item" id="allowanceLi">
                  <a class="nav-link" data-toggle="pill" href="#allowance" role="tab" aria-selected="true">Allowance</a>
                </li>
                @if($query->payroll->payroll_type_id==1 && $query->emp_stat_id!=5)
                <li class="nav-item" id="lwopLi">
                    <a class="nav-link" data-toggle="pill" href="#lwop" role="tab" aria-selected="true">LWOP</a>
                </li>
                @endif
              </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="deduction" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-12">
                                <table id="deductionModalTable" class="table table-bordered table-fixed"
                                        data-toggle="table"
                                        data-search="true"
                                        data-height="600"
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
                                            <th data-field="f2" data-sortable="true" data-align="center">Deduction</th>
                                            <th data-field="f3" data-sortable="true" data-align="center">Group</th>
                                            <th data-field="f4" data-sortable="true" data-align="center">Amount</th>
                                            <th data-field="f5" data-sortable="true" data-align="center">Date From</th>
                                            <th data-field="f6" data-sortable="true" data-align="center">Date To</th>
                                            <th data-field="f7" data-sortable="true" data-align="center">Docs</th>
                                            <th data-field="f8" data-sortable="true" data-align="center">Remarks</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="allowance" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-12">
                                <table id="allowanceModalTable" class="table table-bordered table-fixed"
                                        data-toggle="table"
                                        data-search="true"
                                        data-height="600"
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
                                            <th data-field="f2" data-sortable="true" data-align="center">Allowance</th>
                                            <th data-field="f3" data-sortable="true" data-align="center">Amount</th>
                                            <th data-field="f4" data-align="center"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if($query->payroll->payroll_type_id==1 && $query->emp_stat_id!=5)
                    <div class="tab-pane fade" id="lwop" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-3">
                                <label>Day</label> (<span id="lwopDay">{{$per_salary['day']}}</span>)
                                <input type="number" class="form-control lwopInput" data-n="lwop_day" name="lwop_day" value="{{$query->lwop_day}}">
                                <span id="lwopDayTotal"></span>
                            </div>
                            <div class="col-lg-3">
                                <label>Hour</label> (<span id="lwopHour">{{$per_salary['hour']}}</span>)
                                <input type="number" class="form-control lwopInput" data-n="lwop_hour" name="lwop_hour" value="{{$query->lwop_hour}}">
                                <span id="lwopHourTotal"></span>
                            </div>
                            <div class="col-lg-3">
                                <label>Minute</label> (<span id="lwopMinute">{{$per_salary['minute']}}</span>)
                                <input type="number" class="form-control lwopInput" data-n="lwop_minute" name="lwop_minute" value="{{$query->lwop_minute}}">
                                <span id="lwopMinuteTotal"></span>
                            </div>
                            <div class="col-lg-3">
                                <label>TOTAL</label><br>
                                @if($query->emp_stat->gov=='Y')
                                <button class="btn btn-info btn-info-scan center" style="width: 100%">
                                    @if($query->lwop==NULL)
                                        0.00
                                    @else
                                        {{$query->lwop}}
                                    @endif
                                </button>
                                @else
                                <input type="number" class="form-control lwopInput" data-n="lwop" name="lwop_total" value="{{$query->lwop}}">
                                @endif
                            </div>
                            <div class="col-lg-12"><br></div>
                            <div class="col-lg-3">
                                <label>Lates</label>
                                <input type="number" class="form-control lwopInput" data-n="lates" name="lates" value="{{$query->lates}}">
                            </div>
                            <div class="col-lg-3">
                                <label>Undertime</label>
                                <input type="number" class="form-control lwopInput" data-n="undertime" name="undertime" value="{{$query->undertime}}">
                            </div>
                            <div class="col-lg-3">
                                <label>Absences</label>
                                <input type="number" class="form-control lwopInput" data-n="absences" name="absences" value="{{$query->absences}}">
                            </div>
                        </div>
                        <br><br><br><br><br><br><br><br>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<!-- /.modal-content -->
