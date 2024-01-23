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
