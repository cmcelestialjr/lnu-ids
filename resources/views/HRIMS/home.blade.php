@extends('layouts.header')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <div class="card card-primary card-outline">
          <div class="card-header">
            {{$getTime}}
          </div>
          <div class="card-body">
              <table class="table table-bordered">
              @foreach($getUser as $row)
                <tr>
                  <td>{{$row['uid']}}</td>
                  <td>{{$row['userid']}}</td>
                  <td>{{$row['name']}}</td>
                </tr>
              @endforeach
              @foreach($attendace as $row)
                <tr>
                  <td>{{$row['uid']}}</td>
                  <td>{{$row['id']}}</td>
                  <td>{{$row['state']}}</td>
                  <td>{{$row['timestamp']}}</td>
                  <td>{{$row['type']}}</td> 
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