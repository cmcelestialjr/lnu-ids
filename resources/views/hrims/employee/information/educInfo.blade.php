<div class="card card-primary card-outline">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                <button class="btn btn-primary btn-primary-scan float-right" id="new-educ">
                    <span class="fa fa-plus"></span> New
                </button>
                @endif
            </div>
            <div class="col-lg-12">
                <table id="educTable" class="table table-bordered table-fixed"
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
                            <th data-field="f2" data-sortable="true" data-align="center">Level</th>
                            <th data-field="f3" data-sortable="true" data-align="center">Name</th>
                            <th data-field="f4" data-sortable="true" data-align="center">Period From</th>
                            <th data-field="f5" data-sortable="true" data-align="center">Period To</th>
                            <th data-field="f6" data-sortable="true" data-align="center">Units</th>
                            <th data-field="f7" data-sortable="true" data-align="center">Year Grad</th>
                            <th data-field="f8" data-sortable="true" data-align="center">Honors</th>
                            @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                                <th data-field="f9" data-sortable="true" data-align="center">Option</th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/hrims/employee/information/educ_info.js') }}"></script>
