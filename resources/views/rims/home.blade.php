@extends('layouts.header')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card card-primary card-outline">
        <div class="card-header">
        </div>
        <div class="card-body">
          {{$query}}
          <table class="table table-bordered">
            @foreach($students_data as $row)
            <tr>
              <td>{{$row->stud_id}}</td>
              <td>{{$row->surname}}</td>
              <td>{{$row->first_name}}</td>
            </tr>
          @endforeach
          {{-- @foreach($query as $row)
            <tr>
              <td>{{$row->student_number}}</td>
            </tr>
          @endforeach --}}

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
