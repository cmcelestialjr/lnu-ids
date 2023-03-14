
<div class="modal-content" id="programsModal">
    <div class="modal-header">
        <h4 class="modal-title">
            {{$department->name}} ({{$department->shorten}})
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
           <div class="col-lg-12">
                <button class="btn btn-primary btn-primary-scan">
                    <span class="fa fa-plus-square"></span> Add Program</button>
                <table id="programsList" class="table table-bordered table-fixed"
                            data-toggle="table"
                            data-search="true"
                            data-height="650"
                            data-buttons-class="primary"
                            data-show-export="true"
                            data-show-columns-toggle-all="true"
                            data-pagination="true"
                            data-page-size="10"
                            data-page-list="[10, 50, 100, All]"
                            data-loading-template="loadingTemplate"
                            data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                    <thead>
                        <tr>
                            <th data-field="f1" data-sortable="true" data-align="center">#</th>
                            <th data-field="f2" data-sortable="true" data-align="center">Program</th>
                            <th data-field="f3" data-sortable="true" data-align="center">Shorten</th>                                  
                            <th data-field="f4" data-sortable="true" data-align="center">Code</th>
                            <th data-field="f5" data-sortable="true" data-align="center">Status</th>
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
