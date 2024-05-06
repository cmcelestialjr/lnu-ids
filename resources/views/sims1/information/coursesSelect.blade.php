<label>School Year</label>
<select class="form-control select2-courses" name="school_year" style="width: 90%">
    @foreach($school_year as $row)
      <option value="{{$row->id}}">{{$row->year_from}}-{{$row->year_to}} - {{$row->grade_period->name}}</option>
    @endforeach    
</select>
<div class="col-lg-12" id="coursesList">

</div>