<div class="row">
    <div class="col-lg-5">
        <label>Branch</label>
        <select class="form-control select2-programsSelect" name="branch">
            @foreach($branch as $row)
                <option value="{{$row->id}}">{{$row->code}}-{{$row->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-6">
        <label>Programs</label>
        <select class="form-control select2-programsSelect" name="program">
            @foreach($program as $row)
                <option value="{{$row->id}}">{{$row->shorten}}-{{$row->name}}</option>
            @endforeach
        </select>
    </div>
</div>