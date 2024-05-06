<label>Section</label>
<select class="form-control select2-section" name="program_section">
    @foreach($program_section as $row)
        <option value="{{$row->section}}">{{$row->section}}</option>
    @endforeach
</select>