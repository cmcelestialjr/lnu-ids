
<div class="modal-content" id="studentsListModal">
    <div class="modal-header">
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 table-responsive"><br>
                                <table class="table">
                                    <tr>
                                        <td>
                                            Course Code:
                                        </td>
                                        <td>
                                            <label>{{$query->code}}</label>
                                        </td>
                                        <td>
                                            Section Code:
                                        </td>
                                        <td>
                                            <label>{{$query->section_code}}</label>
                                        </td>
                                        <td>
                                            Units:
                                        </td>
                                        <td>
                                            <label>{{$query->course->units}}</label>
                                        </td>                                        
                                    </tr>
                                    <tr>
                                        <td>
                                            Schedule:
                                        </td>
                                        <td>
                                            <label>{!!$schedule!!}</label>
                                        </td>
                                        <td>
                                            Room:
                                        </td>
                                        <td>
                                            <label>{{$room}}</label>
                                        </td>
                                        <td>
                                            Status:
                                        </td>
                                        <td>
                                            <label>{!!$status!!}</label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card card-info card-outline">
                    <div class="card-body">
                        <table id="studentsListTable" class="table table-bordered table-fixed"
                                data-toggle="table"
                                data-search="true"
                                data-height="450"
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
                                    <th data-field="f3" data-sortable="true" data-align="center">ID No.</th>
                                    <th data-field="f4" data-sortable="true" data-align="center">Program</th>
                                    <th data-field="f5" data-sortable="true" data-align="center">Level</th>
                                    <th data-field="f6" data-sortable="true" data-align="center">Grade</th>
                                </tr>
                            </thead>
                        </table>
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
