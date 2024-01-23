
<div class="modal-content" id="programsAddModal">
    <div class="modal-header">
        <h4 class="modal-title">
            <span class="fa fa-plus-square"></span> Add Program to <label>{{$department->name}} ({{$department->shorten}})</label>
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
           <div class="col-lg-12">
                
                <input type="hidden" name="id" value="{{$id}}">
                <table id="programAddList" class="table table-bordered table-fixed"
                            data-toggle="table"
                            data-search="true"
                            data-height="460"
                            data-buttons-class="primary"
                            data-show-export="true"
                            data-show-columns-toggle-all="true"
                            data-mobile-responsive="true"
                            data-pagination="false"
                            data-loading-template="loadingTemplate"
                            data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                    <thead>
                        <tr>
                            <th data-field="f1" data-sortable="true" data-align="center"></th>
                            <th data-field="f2" data-sortable="true" data-align="center">#</th>
                            <th data-field="f3" data-sortable="true" data-align="center">Department</th>
                            <th data-field="f4" data-sortable="true" data-align="center">Program</th>
                            <th data-field="f5" data-sortable="true" data-align="center">Shorten</th>                                  
                            <th data-field="f6" data-sortable="true" data-align="center">Code</th>
                            <th data-field="f7" data-sortable="true" data-align="center">Status</th>
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
