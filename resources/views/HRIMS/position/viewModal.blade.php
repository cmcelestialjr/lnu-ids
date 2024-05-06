
<div class="modal-content" id="viewModal">
    <div class="modal-header">
        <h4><span class="fa fa-info"></span> {{$query->item_no}} - {{$query->name}} ({{$query->shorten}})</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table id="positionViewTable" class="table table-bordered table-fixed"
                                data-toggle="table"
                                data-search="true"
                                data-height="600"
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
                                    <th data-field="f2" data-sortable="true" data-align="center">Date From</th>
                                    <th data-field="f3" data-sortable="true" data-align="center">Date to</th>
                                    <th data-field="f4" data-sortable="true" data-align="center">ID No.</th>
                                    <th data-field="f5" data-sortable="true" data-align="center">Name</th>
                                    <th data-field="f6" data-sortable="true" data-align="center">Salary</th>
                                    <th data-field="f7" data-sortable="true" data-align="center">SG</th>
                                    <th data-field="f8" data-sortable="true" data-align="center">Step</th>
                                    <th data-field="f9" data-sortable="true" data-align="center">Separation Cause</th>
                                    <th data-field="f10" data-sortable="true" data-align="center">Separation Date</th>
                                    <th data-field="f11" data-sortable="true" data-align="center">Remarks</th>
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
