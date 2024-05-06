<label>Level</label>
<select class="form-control select2-div" name="level" id="discountLevelSelect">
    @foreach($levels as $row)
        @if($row->id==6)
            <option value="{{$row->id}}" selected>{{$row->name}}</option>
        @else
            <option value="{{$row->id}}">{{$row->name}}</option>
        @endif
    @endforeach
</select><br>
<div class="table-responsive" style="height:500px;" id="discountProgramList">
    <table class="table table-bordered">
        <thead>
            <th>Program</th>
            <th>Shorten</th>
            <th><input type="checkbox" class="form-control" name="all" checked></th>
        </thead>
        <tbody>
            @foreach($programs as $row)
                <tr>
                    <td>{{$row->name}}</td>
                    <td>{{$row->shorten}}</td>
                    <td><input type="checkbox" class="form-control programs" value="{{$row->id}}" checked></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
</div>