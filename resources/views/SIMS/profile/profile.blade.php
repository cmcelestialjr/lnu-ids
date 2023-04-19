@extends('layouts.header')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">{{$system_selected}}</a></li>
            <li class="breadcrumb-item active">{{mb_strtoupper($nav_selected)}}</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->


<!-- Content -->
<div class="content" id="profileDiv">
  <!-- Container-fluid -->
    <div class="container-fluid">
    <h1 class="header-text">Student Profile</h1>
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
    </div><!-- /.container-fluid -->
</div>
<!-- /.Content -->

@include('layouts.script')
<script src="{{ asset('assets/js/rims/departments/_function.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/view.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/modal.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/new.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/update.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/delete.js') }}"></script>
@endsection