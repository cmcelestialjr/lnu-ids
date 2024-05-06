@php
$units = 0;
if($course->units>0){
    $units = $course->units;
}
$lab = 0;
if($course->lab>0){
    $lab = $course->lab;
}
$pay_units = 0;
if($course->pay_units>0){
    $pay_units = $course->pay_units;
}
$pre_name = 'None';
if(strlen($course->pre_name)>0){
    $pre_name = $course->pre_name;
}
@endphp
<div class="row" id="courseInfo">
    <div class="col-md-12">
        <br>
    </div>
    <div class="col-md-3">
        <label>Course Code</label>
        <input type="text" class="form-control req" name="code" value="{{$course->code}}">
    </div>
    <div class="col-md-7">
        <label>Descriptive Title</label>
        <input type="text" class="form-control req" name="name" value="{{$course->name}}">
    </div>
    <div class="col-md-2">
        <label>Lab Group</label>
        <select class="form-control select2-div" name="lab_group">
            <option value="None">None</option>
            @foreach($lab_group as $row)
                <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label>Period</label>
        <select class="form-control select2-div" name="grade_period">
            @foreach($grade_period as $row)
                @if($course->grade_period_id==$row->id)
                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                @else
                    <option value="{{$row->id}}">{{$row->name}}</option>
                @endif                        
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label>Year Level</label>
        <select class="form-control select2-div" name="year_level">
            @foreach($year_level as $row)
                @if($course->grade_level_id==$row->id)
                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                @else
                    <option value="{{$row->id}}">{{$row->name}}</option>
                @endif                         
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label>Units</label>
        <input type="number" class="form-control req" name="units" value="{{$units}}">
    </div>
    <div class="col-md-2">
        <label>Lab</label>
        <input type="number" class="form-control req" name="lab" value="{{$lab}}">
    </div>
    <div class="col-md-2">
        <label>Pay</label>
        <input type="number" class="form-control req" name="pay_units" value="{{$pay_units}}">
    </div>
    <div class="col-md-12">
        <br>
        <label>Pre-requisite</label>
        <div class="row">
            <div class="col-lg-6">
                <label>Name appear in Pre-requisite</label>
                <input type="text" class="form-control" name="pre_name" value="{{$pre_name}}">
            </div>
            <div class="col-lg-4">
            </div>
            <div class="col-lg-2">
                <br><br>
                <div class="form-group clearfix">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="checkboxPrimary1" class="all">
                        <label for="checkboxPrimary1">
                            Check All
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>