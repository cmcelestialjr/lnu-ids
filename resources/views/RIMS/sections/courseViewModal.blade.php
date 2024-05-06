<div class="modal-content" id="courseViewModal">
    <div class="modal-header">
        <h4 class="modal-title">
           Section {{$query->section}} - {{$query->section_code}}
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-info card-outline">
                    <div class="card-body table-responsive">
                      <table id="courseViewTable" class="table table-bordered table-fixed"
                                  data-toggle="table"
                                  data-search="true"
                                  data-height="500"
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
                                  <th data-field="f1" data-sortable="true" data-align="center" rowspan="2">#</th>
                                  <th data-field="f2" data-sortable="true" data-align="center" rowspan="2">Descriptive Title</th>
                                  <th data-field="f3" data-sortable="true" data-align="center" rowspan="2">Code</th>
                                  <th data-field="f4" data-sortable="true" data-align="center" rowspan="2">Units</th>                                  
                                  <th data-align="center" colspan="2">Student</th>
                                  <th data-field="f7" data-sortable="true" data-align="center" rowspan="2">Schedule</th>
                                  <th data-field="f8" data-sortable="true" data-align="center" rowspan="2">Room</th>
                                  <th data-field="f9" data-sortable="true" data-align="center" rowspan="2">Instructor</th>
                              </tr>
                              <tr>
                                  <th data-field="f5" data-sortable="true" data-align="center">Min-Max</th>
                                  <th data-field="f6" data-sortable="true" data-align="center">Total</th>
                              </tr>
                          </thead>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
    </div>
</div>
<!-- /.modal-content -->
