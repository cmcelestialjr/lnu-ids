
<div class="modal-content" id="programCodesModal">
    <div class="modal-header">
        <h4 class="modal-title">
            {{$program->name}} ({{$program->shorten}})
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
           <div class="col-lg-12">
                <button class="btn btn-primary btn-primary-scan programCodeNewModal" style="float:right;"
                        data-id="{{$id}}">
                <span class="fa fa-plus-square"></span> New Code</button>
                <br><br>
                <table id="programCodesList" class="table table-bordered table-fixed"
                            data-toggle="table"
                            data-search="true"
                            data-height="400"
                            data-buttons-class="primary"
                            data-show-export="true"
                            data-show-columns-toggle-all="true"
                            data-mobile-responsive="true"
                            data-pagination="true"
                            data-loading-template="loadingTemplate"
                            data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                    <thead>
                        <tr>
                            <th data-field="f1" data-sortable="true" data-align="center">#</th>
                            <th data-field="f2" data-sortable="true" data-align="center">Code</th>
                            <th data-field="f3" data-sortable="true" data-align="center">Remarks</th>
                            <th data-field="f4" data-sortable="true" data-align="center">Status</th>
                            <th data-field="f5" data-sortable="true" data-align="center">Edit</th>
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
