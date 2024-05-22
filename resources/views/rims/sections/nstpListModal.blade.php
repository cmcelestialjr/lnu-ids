<div class="modal-content" id="nstpEditCountModal">
    <div class="modal-header">
        <h4 class="modal-title">
            <span class="fa fa-info"></span> {{$nstp->code}} - {{$nstp->section_code}}
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-info card-outline">
                    <div class="card-body table-responsive">
                        <table id="studentListTable" class="table table-bordered table-fixed"
                            data-toggle="table"
                            data-search="true"
                            data-height="460"
                            data-buttons-class="primary"
                            data-show-export="true"
                            data-show-columns-toggle-all="true"
                            data-mobile-responsive="true"
                            data-pagination="true"
                            data-page-size="10"
                            data-page-list="[10, 50, 100, All]"
                            data-loading-template="loadingTemplate"
                            data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']"
                            style="width: 100%">
                                <thead>
                                    <tr>
                                        <th data-field="f1" data-sortable="true" data-align="center">#</th>
                                        <th data-field="f2" data-sortable="true" data-align="center">Name</th>
                                        <th data-field="f3" data-sortable="true" data-align="center">Department</th>
                                        <th data-field="f4" data-sortable="true" data-align="center">Program</th>
                                        <th data-field="f5" data-sortable="true" data-align="center">Grade</th>
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
