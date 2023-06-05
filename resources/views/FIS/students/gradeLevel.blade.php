<label>Level</label>
<select class="form-control select2-gradeLevel" name="level[]" multiple>
    @foreach($program_level as $row)
        <option value="{{$row->id}}">{{$row->name}}</option>
    @endforeach
</select>