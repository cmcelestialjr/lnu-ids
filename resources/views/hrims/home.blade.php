@extends('layouts.header')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <div class="card card-primary card-outline">
          <div class="card-header">
            {{$deviceName}}
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <h4>The Human Resource Information Management System (HRIMS) efficiently handles the input and management of data related to employee information and HR processes:
                        <br><br>Employee Information: HRIMS collects and maintains employee data, including personal details, contact information, job positions, qualifications, and work history. This information can be updated by HR personnel or employees themselves.
                        <br><br>Payroll Processing: The system automates payroll processes, including salary calculation, deduction management, and tax calculations. It generates payslips for employees and handles remittances.
                        <br><br>Attendance Tracking: The system maintains daily time records by allowing employees to time in and out using a biometric machine, which automatically records the data. Email notifications are sent automatically for each time entry. By monitoring attendance, the system generates reports to evaluate employee punctuality and absences.
                        </h4>
                </div>
                {{-- <div class="col-lg-6">
                    <table class="table table-bordered">
                        @foreach($getUser as $row)
                          <tr>
                            <td>{{$row['userid']}}</td>
                          </tr>
                        @endforeach
                    </table>
                </div> --}}
                {{-- <div class="col-lg-6">
                    <table class="table table-bordered">
                        @foreach($getUser1 as $row)
                          <tr>
                            <td>{{$row}}</td>
                          </tr>
                        @endforeach
                    </table>
                </div> --}}
            </div>
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
