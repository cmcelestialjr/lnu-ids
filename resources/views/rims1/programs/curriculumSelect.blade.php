<select class="form-control select2-default" name="curriculum">
    @foreach($curriculums as $row)
        <option value="{{$row->id}}">{{$row->year_from}} - {{$row->year_to}} ({{$row->code}}) ({{$row->status->name}})</option>
    @endforeach
</select>