<div class="modal-content" id="docsModal">
    <div class="modal-header">
        <h4><span class="fa fa-edit"></span> {{$deduction->name}}</h4>
    </div>
    <div class="modal-body">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
              <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item" id="deductionLi">
                    <a class="nav-link active" data-toggle="pill" href="#docs" role="tab" aria-selected="true">Docs</a>
                </li>
                <li class="nav-item" id="allowanceLi">
                  <a class="nav-link" data-toggle="pill" href="#new" role="tab" aria-selected="true">New</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="docs" role="tabpanel">
                        <div class="row">
                            <input type="hidden" name="id" value="{{$deduction->id}}">
                            <div class="col-lg-3">
                                <label>Year</label>
                                <select class="form-control select2-primary" name="year">
                                    @for($i=date('Y'); $i >= 2022; $i--)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-1"><br>
                                <button class="btn btn-info btn-info-scan" name="submit">
                                    <span class="fa fa-check"></span>
                                </button>
                            </div>
                            <div class="col-lg-12"><br>
                                <table id="docsTable" class="table table-bordered table-fixed"
                                        data-toggle="table"
                                        data-search="true"
                                        data-height="500"
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
                                            <th data-field="f2" data-sortable="true" data-align="center">View</th>
                                            <th data-field="f3" data-sortable="true" data-align="center">Amount</th>
                                            <th data-field="f4" data-sortable="true" data-align="center">Date From</th>
                                            <th data-field="f5" data-sortable="true" data-align="center">Date To</th>
                                            <th data-field="f6" data-sortable="true" data-align="center">Remarks</th>
                                            <th data-field="f7" data-sortable="true" data-align="center">By</th>
                                            <th data-field="f8" data-sortable="true" data-align="center">DateTime</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="new" role="tabpanel">
                        <form id="form" enctype="multipart/form-data">
                            <input type="hidden" name="did" value="{{$deduction->id}}">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label>Application No.</label>
                                    <input type="text" class="form-control" name="account_no">
                                </div>
                                <div class="col-lg-3">
                                    <label>Monthly</label>
                                    <input type="number" class="form-control" name="amount">
                                </div>
                                <div class="col-lg-3">
                                    <label>Total Loan</label>
                                    <input type="number" class="form-control" name="total_amount">
                                </div>
                                <div class="col-lg-3">
                                    <label>Date From</label>
                                    <input type="text" class="form-control datePicker1" name="date_from">
                                </div>
                                <div class="col-lg-3">
                                    <label>Date To</label>
                                    <input type="text" class="form-control datePicker1" name="date_to">
                                </div>
                                <div class="col-lg-3">
                                    <label>Remarks</label>
                                    <textarea name="remarks" style="width:100%"></textarea>
                                </div>
                                <div class="col-lg-12"></div>
                                <div class="col-lg-2"></div>
                                <div class="col-lg-7"><br>
                                    <div class="file-drop-area">
                                        <button class="btn btn-primary btn-primary-scan">Choose file</button>
                                        &nbsp; <span class="file-message">or drag and drop file here</span>
                                        <input class="file-input" type="file" name="files[]" multiple accept=".jpg, .jpeg, .png, .pdf">
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-success progress-bar-striped" role="progressbar" id="progress-bar"
                                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                          <span class="sr-only">0% Complete (success)</span>
                                        </div>
                                      </div>
                                    <button class="btn btn-primary btn-primary-scan" name="submit" style="width: 100%;">Submit Import</button>
                                </div>
                            </div>
                        </form>
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
