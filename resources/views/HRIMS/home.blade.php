@extends('layouts.header')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <div class="card card-primary card-outline">
          <div class="card-header">
            {{$deviceName}}
          </div>
          <div class="card-body">
              <table class="table table-bordered">
              @foreach($getTime as $row)
                <tr>
                  <td>{{$row->DeviceId}}</td>
                  <td>{{$row->LogDate}}</td>
                </tr>
              @endforeach
              </table>
          </div>
          <div class="card-footer">
              
          </div>
      </div>
  </div>
<!-- /.col-md-6 -->
</div>
<!-- /.row -->
@include('layouts.script')
@endsection