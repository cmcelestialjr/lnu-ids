
<div class="modal-content" id="branchUpdate">
    <div class="modal-header">
        <h4 class="modal-title">
            {{$program->shorten}}<br>{{$program->name}}
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <table id="branchTable" class="table table-bordered table-fixed"
                        data-toggle="table"
                        data-search="true"
                        data-height="300"
                        data-buttons-class="primary"
                        data-show-export="true"
                        data-show-columns-toggle-all="true"
                        data-mobile-responsive="true"
                        data-pagination="true"
                        data-page-size="15"
                        data-page-list="[15, 10, 50, 100, All]"
                        data-loading-template="loadingTemplate"
                        data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                    <thead>
                        <tr>
                            <th data-field="f1" data-sortable="true" data-align="center">#</th>
                            <th data-field="f2" data-sortable="true" data-align="center">Name</th>
                            <th data-field="f3" data-sortable="true" data-align="center">Code</th>
                            <th data-field="f4" data-sortable="true" data-align="center">Status</th>
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
