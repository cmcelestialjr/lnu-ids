
<div class="modal-content" id="sectionViewModal">
    <div class="modal-header">
        <h4 class="modal-title">
          {{$query->curriculum->offered_program->name}} - {{$query->curriculum->offered_program->program->name}} ({{$query->curriculum->offered_program->program->shorten}})<br>
          {{$query->curriculum->curriculum->year_from}} - {{$query->curriculum->curriculum->year_to}} ({{$query->curriculum->code}}) ({{$query->course->grade_level->name}})
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-info card-outline">
                    <div class="card-body table-responsive">
                      <table id="sectionViewTable" class="table table-bordered table-fixed"
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
                                  <th data-field="f1" data-sortable="true" data-align="center">#</th>
                                  <th data-field="f2" data-sortable="true" data-align="center">Section</th>
                                  <th data-field="f3" data-sortable="true" data-align="center">Code</th>
                                  <th data-field="f4" data-sortable="true" data-align="center">Courses</th>
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
