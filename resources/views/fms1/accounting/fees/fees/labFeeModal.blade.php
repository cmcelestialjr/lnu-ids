
<div class="modal-content" id="labFeesModal">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> Lab Fees</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
              <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item" id="labCourses">
                    <a class="nav-link active" data-toggle="pill" href="#lab_courses" role="tab" aria-selected="false">Lab Courses</a>
                </li>
                <li class="nav-item" id="labGroup">
                  <a class="nav-link" data-toggle="pill" href="#lab_group" role="tab" aria-selected="false">Lab Group</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="lab_courses" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="labCoursesTable" class="table table-bordered table-fixed"
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
                                            <th data-field="f2" data-sortable="true" data-align="center">Course Code</th>
                                            <th data-field="f3" data-sortable="true" data-align="center">Group</th>
                                            <th data-field="f4" data-sortable="true" data-align="center">Fee</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="lab_group" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-primary-scan" name="new" style="float: right">
                                    <span class="fa fa-plus"></span> New Group
                                </button><br><br>
                                <table id="labGroupTable" class="table table-bordered table-fixed"
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
                                            <th data-field="f2" data-sortable="true" data-align="center">Name</th>
                                            <th data-field="f3" data-sortable="true" data-align="center">Remarks</th>
                                            <th data-field="f4" data-sortable="true" data-align="center">Option</th>
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
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-plus"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
