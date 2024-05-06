<tr>
    <td><input type="text" class="form-control course_code"></td>
    <td><input type="text" class="form-control course_desc"></td>
    <td><input type="number" class="form-control unit"></td>
    <td><input type="number" class="form-control lab" value="0"></td>
    <td>
        <select class="form-control select2-tr{{$length}} statuses" data-n="{{$length}}" id="course_statuses{{$length}}">
            @foreach($statuses as $row)
            <option value="{{$row->id}}" data-option="{{$row->option}}">{{$row->shorten}} - {{$row->name}}</option>
            @endforeach
        </select>
    </td>
    <td><input type="number" class="form-control rating" id="rating_{{$length}}"></td>
    <td>
        <button class="btn btn-danger btn-danger-scan btn-xs remove"><span class="fa fa-minus"></span></button>
    </td>
</tr>