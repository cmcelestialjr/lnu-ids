
<div class="modal-content" id="coursesViewModal">
    <div class="modal-header">
        <h4 class="modal-title">
           <span class="fa fa-user"></span>
           {{$query->id_no}} {{$name}} ({{$query->grade_level->name}})
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <h4>Courses Taken</h4>
                <table id="coursesViewTable" class="table table-bordered table-fixed"
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
                            <th data-field="f2" data-sortable="true" data-align="center">Program</th>
                            <th data-field="f3" data-sortable="true" data-align="center">Curriculum</th>
                            <th data-field="f4" data-sortable="true" data-align="center">Course</th>                            
                            <th data-field="f5" data-sortable="true" data-align="center">Level</th>
                            <th data-field="f6" data-sortable="true" data-align="center">Section</th>                            
                            <th data-field="f7" data-sortable="true" data-align="center">Units</th>
                            <th data-field="f8" data-sortable="true" data-align="center">Schedule</th>
                            <th data-field="f9" data-sortable="true" data-align="center">Room</th>
                            <th data-field="f10" data-sortable="true" data-align="center">Instructor</th>
                            <th data-field="f11" data-sortable="true" data-align="center">Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
    </div>
</div>
<!-- /.modal-content -->
