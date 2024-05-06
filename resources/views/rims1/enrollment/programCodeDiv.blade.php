<label>Code</label>
<select class="form-control select2-program" name="program_code">
    @foreach($program_codes as $row)
        <option value="{{$row->id}}">{{$row->name}}</option>
    @endforeach
</select>