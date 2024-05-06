
<div class="modal-content">
    <div class="modal-header">
        <h4><span class="fa fa-info"></span> {{$query->name}}</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table id="groupViewModalTable" class="table table-bordered table-fixed"
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
