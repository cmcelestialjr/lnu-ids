
<div class="modal-content" id="clusterViewModal">
    <div class="modal-header">
        <h4><span class="fa fa-info"></span> {{$query->code}} - {{$query->name}} ({{$query->shorten}})</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table id="viewClusterTable" class="table table-bordered table-fixed"
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
                                    <th data-field="f2" data-sortable="true" data-align="center">Name</th>
                                    <th data-field="f3" data-sortable="true" data-align="center">Shorten</th>
                                    <th data-field="f4" data-sortable="true" data-align="center">Code</th>
                                    <th data-field="f5" data-sortable="true" data-align="center">UACS</th>
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
<!-- /.modal-content -->
