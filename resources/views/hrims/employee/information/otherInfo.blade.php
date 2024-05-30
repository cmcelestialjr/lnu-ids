<div class="card card-primary card-outline">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                <button class="btn btn-primary btn-primary-scan float-right" id="new-other">
                    <span class="fa fa-plus"></span> New
                </button>
                @endif
            </div>
            <div class="col-lg-4">
                <table id="otherSkillTable" class="table table-bordered table-fixed"
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
                            <th data-field="f2" data-sortable="true" data-align="center">Skills & Hobbies</th>
                            @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                                <th data-field="f3" data-sortable="true" data-align="center">Option</th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-lg-4">
                <table id="otherRecognitionTable" class="table table-bordered table-fixed"
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
                            <th data-field="f2" data-sortable="true" data-align="center">Recognitions</th>
                            @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                                <th data-field="f3" data-sortable="true" data-align="center">Option</th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-lg-4">
                <table id="otherOrganizationTable" class="table table-bordered table-fixed"
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
                            <th data-field="f2" data-sortable="true" data-align="center">Membership in Organization</th>
                            @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                                <th data-field="f3" data-sortable="true" data-align="center">Option</th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/hrims/employee/information/other_info.js') }}"></script>
