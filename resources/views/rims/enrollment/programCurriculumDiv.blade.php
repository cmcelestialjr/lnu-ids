<label>Curriculum</label>
<select class="form-control select2-curriculum" name="program_curriculum">
    @foreach($program_curriculum as $row)
        @if($student->curriculum_id==$row->curriculum_id)
            <option value="{{$row->id}}" selected>{{$row->curriculum->year_from}} - {{$row->curriculum->year_to}} ({{$row->code}})</option>
        @else
            <option value="{{$row->id}}">{{$row->curriculum->year_from}} - {{$row->curriculum->year_to}} ({{$row->code}})</option>
        @endif
    @endforeach
</select>
