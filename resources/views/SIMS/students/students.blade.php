@extends('layouts.header')
@section('content')
<div class="row" id="profileDiv">
    <div class="col-lg-12">
    <h1 class="header-text">Students</h1>
        <div class="card card-primary card-tabs">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if($user_access->level_id==1 || $user_access->level_id==2)
                        <br><br>
                        @endif
                        <table id="viewTable" class="table table-bordered table-fixed"
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
                                   
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.script')
<script src="{{ asset('assets/js/rims/departments/_function.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/view.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/modal.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/new.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/update.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/delete.js') }}"></script>
@endsection