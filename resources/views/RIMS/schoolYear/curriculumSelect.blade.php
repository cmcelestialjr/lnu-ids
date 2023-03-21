<label>Curriculum</label>
<select class="form-control select2-programsSelect" name="curriculum">
    <option value="">Please Select</option>
    @foreach($curriculums as $row)        
        <option value="{{$row->id}}">{{$row->year_from}} - {{$row->year_to}}</option>
    @endforeach
</select>