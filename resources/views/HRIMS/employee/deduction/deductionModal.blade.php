
<div class="modal-content" id="deductionModal">
    <div class="modal-header">
        <h4><span class="fa fa-edit"></span> {{$query->lastname}}, {{$query->firstname}} {{$query->extname}} {{$query->middlename}}</h4>
        <input type="hidden" name="id" value="{{$query->id}}">
        <input type="hidden" name="li" value="deduction">
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    {{-- <div class="col-lg-12">
                        <table class="table">
                            <tr>
                                <td style="width: 20%"><label>Salary: {{number_format($query->employee_default->salary,2)}}</label></td>
                                <td style="width: 20%"><label>Allowance: <label id="allowances" class="text-primary"></label></label></td>
                                <td style="width: 20%"><label>Gross: <label id="gross"></label></label></td>
                                <td style="width: 20%"><label>Deduction: <label id="deductions" class="text-require"></label></label></td>
                                <td style="width: 20%"><label>Netpay: <label id="netpay" class="text-success"></label></label></td>
                            </tr>
                        </table>
                    </div> --}}
                    <div class="col-lg-4">
                        <label>Payroll Type</label>
                        <select class="form-control select2-default" name="payroll_type">
                            @foreach($payroll_type as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                        <br>
                    </div>
                    <div class="col-lg-4">
                        <label>Employment Status</label>
                        <select class="form-control select2-default" name="emp_stat">
                            @foreach($emp_stat as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                        <br>
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
                      </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="deduction" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-4">
                                        
                                    </div>
                                    <div class="col-lg-12">
                                        <table id="deductionTable" class="table table-bordered table-fixed"
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
                                        <table id="allowanceTable" class="table table-bordered table-fixed"
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
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>        
    </div>
</div>
<!-- /.modal-content -->
