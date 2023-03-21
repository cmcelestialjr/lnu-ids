<label>Programs</label>
<select class="form-control select2-programsSelect" name="program">
    @foreach($query as $row)
        <option value="{{$row->id}}">{{$row->name}} - {{$row->program->name}} ({{$row->program->shorten}})</option>
    @endforeach
</select>