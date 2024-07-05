
<div class="col-lg-12">
    <br>
</div>
<div class="col-lg-3">
    <label>School Year</label> <a href="#" id="partTimeSYNew"><span class="fa fa-plus"></span></a>
    <select class="form-control select2-div" id="partTimeSY">
        @foreach($school_years as $sy)
            <option value="{{$sy->id}}">{{$sy->year_from}}-{{$sy->year_to}} {{$sy->grade_period->name_no}}</option>
        @endforeach
        <option value="">Please select...</option>
    </select>
</div>
<div class="col-lg-3">
    <label>Type</label>
    <select class="form-control select2-div" name="options[]" id="partTimeOption" multiple>
        @foreach ($options as $row)
            <option value="{{$row->id}}">{{$row->name}}</option>
        @endforeach
    </select>
</div>
<div class="col-lg-12">
    <button class="btn btn-primary btn-primary-scan" id="partTimeAdd" style="float:right;">
        <span class="fa fa-plus"> Add Employee</span>
    </button>
    <br>
</div>
<div class="col-lg-12" id="partTimeDiv">

</div>
<script src="{{ asset('assets/js/hrims/payroll/monitoring/partTime.js') }}"></script>
