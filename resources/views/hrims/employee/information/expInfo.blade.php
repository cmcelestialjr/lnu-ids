<div class="card card-primary card-outline">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <table id="expTable" class="table table-bordered table-fixed"
                    data-toggle="table"
                    data-search="true"
                    data-buttons-class="primary"
                    data-show-export="true"
                    data-show-columns-toggle-all="true"
                    data-mobile-responsive="true"
                    data-pagination="true"
                    data-page-size="5"
                    data-page-list="[5, 50, 100, All]"
                    data-loading-template="loadingTemplate"
                    data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                    <thead>
                        <tr>
                            <th data-field="f1" data-sortable="true" data-align="center">#</th>
                            <th data-field="f2" data-sortable="true" data-align="center">Date From</th>
                            <th data-field="f3" data-sortable="true" data-align="center">Date To</th>
                            <th data-field="f4" data-sortable="true" data-align="center">Position Title</th>
                            <th data-field="f5" data-sortable="true" data-align="center">Salary</th>
                            <th data-field="f6" data-sortable="true" data-align="center">SG</th>
                            <th data-field="f7" data-sortable="true" data-align="center">Status</th>
                            <th data-field="f8" data-sortable="true" data-align="center">Gov't?</th>
                            <th data-field="f9" data-sortable="true" data-align="center">Docs</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/hrims/employee/information/exp_info.js') }}"></script>
