<label>Grade Level</label>
<select class="form-control select2-gradeLevelSelect" name="grade_level">
    @foreach($grade_level as $row)
        <option value="{{$row->id}}">{{$row->name}}</option>
    @endforeach
</select>