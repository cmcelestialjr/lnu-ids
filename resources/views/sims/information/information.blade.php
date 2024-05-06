@extends('layouts.header')
@section('content')
<div class="row" id="information" style="padding-left: 20px;padding-right: 20px;">
    <div class="col-lg-3">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row" style="height:350px">
                    <div class="col-lg-12 center">
                        <br><br>
                        <img src="{{ asset('assets/images/icons/png/user.png') }}" class="profile-img">
                        <br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        {{-- <div class="card card-primary card-outline bg-gradient-lightblue"> --}}
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row table-responsive" style="height:350px">
                    <div class="col-lg-12">
                        <label>Personal Information <a href="#" class="information_edit" data-id="personalInfoEdit"><span class="fa fa-edit"></span></a></label>
                        <div id="informationPersonalInfo">
                            <table class="table">
                                <tr>
                                    <td style="width: 30%">Student No:</td>
                                    <td style="width: 70%"><label>{{$info->stud_id}}</label></td>
                                </tr>
                                <tr>
                                    <td>Name:</td>
                                    <td><label>{{$info->firstname}} {{$info->middlename}} {{$info->lastname}} {{$info->extname}}</label></td>
                                </tr>
                                <tr>
                                    <td>Contact:</td>
                                    <td><label>{{$info->personal_info->contact_no}}</label></td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td><label>{{$info->personal_info->email}}</label></td>
                                </tr>
                                <tr>
                                    <td>Sex:</td>
                                    <td><label>{{$info->personal_info->sexs->name}}</label></td>
                                </tr>
                                <tr>
                                    <td>Civil Status:</td>
                                    <td><label>
                                        @if($info->personal_info->civil_status_id)
                                            {{$info->personal_info->civil_statuses->name}}
                                        @endif
                                        </label></td>
                                </tr>
                                <tr>
                                    <td>Birthdate:</td>
                                    <td><label>
                                        @if($info->personal_info->dob)
                                            {{date('F d, Y', strtotime($info->personal_info->dob))}}
                                        @endif
                                        </label></td>
                                </tr>
                                <tr>
                                    <td>Birthplace:</td>
                                    <td><label>{{$info->personal_info->place_birth}}</label></td>
                                </tr>
                                <tr>
                                    <td>Religion:</td>
                                    <td><label>
                                        @if($info->personal_info->religion_id)
                                            {{$info->personal_info->religion->name}}
                                        @endif
                                        </label></td>
                                </tr>
                                <tr>
                                    <td>Address:</td>
                                    <td><label></label></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row table-responsive" style="height:350px">
                    <div class="col-lg-12">
                        <label>Educational Background <a href="#" class="information_edit" data-id="educationalBgEdit"><span class="fa fa-edit"></span></a></label>                        
                        <table class="table" id="informationEducBg">
                            @foreach($education_level as $row)
                            <tr>
                                <td>
                                    <label>{{$row->name}}</label>
                                    @if($row->education_bg)
                                        <div class="card card-info card-outline">
                                            <div class="card-body">
                                        @foreach($row->education_bg as $subRow)
                                            {{$subRow->name}}<br>
                                            @if($subRow->program)
                                                {{$subRow->program->name}}<br>
                                            @endif
                                            {{date('M d, Y',strtotime($subRow->period_from))}} -
                                            @if($subRow->period_to=='present')
                                              {{$subRow->period_to}}
                                            @else
                                              {{date('M d, Y',strtotime($subRow->period_to))}}
                                            @endif
                                            <br>
                                            @if($subRow->year_grad)
                                            Year Graduated: {{$subRow->year_grad}}<br>
                                            @endif
                                            @if($subRow->honors)
                                            Honors: {{$subRow->honors}}
                                            @endif
                                            <div class="card card-default card-outline"></div>
                                        @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row table-responsive" style="height:350px">
                    <div class="col-lg-12">
                        <label>Family Background <a href="#" class="information_edit" data-id="familyBgEdit"><span class="fa fa-edit"></span></a></label>
                        <div id="informationFamBg">
                            @foreach($family_bg as $row)
                            <div class="card card-info card-outline">
                                <div class="card-body">
                                    {{$row->fam_relation->name}}: {{$row->firstname}} {{$row->middlename}} {{$row->lastname}} {{$row->extname}}<br>
                                    Birthdate: {{date('F d, Y',strtotime($row->dob))}}<br>
                                    Contact: {{$row->contact_no}}
                                    @if($row->email)
                                    <br>Email: {{$row->email}}
                                    @endif
                                    @if($row->occupation)
                                    <br>Occupation: {{$row->occupation}}
                                    @endif
                                    @if($row->employer)
                                    <br>Employer: {{$row->employer}}
                                    @endif
                                    @if($row->employer_address)
                                    <br>Employer Address: {{$row->employer_address}}
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">        
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <select class="form-control select2" name="option">
                            <option value="1">Curriculum</option>
                            <option value="2">School Year</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <select class="form-control select2" name="program_level">
                            @foreach($program_level as $row)
                              <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12" id="selectOption">
                      <br><br><br><br><br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('layouts.script')
<script src="{{ asset('assets/js/sims/information/information.js') }}"></script>
@endsection