<div class="card-header">
    <h3 class="card-title">
        Main Nav <b>({{$system_selected->shorten}})</b>
    </h3>
</div>
<!-- /.card-header -->
<div class="card-body">
    <table style="width:100%">
        @foreach($systems_nav as $row)
            <tr>
                <td  style="width:50%">
                    <button class="btn btn-info btn-info-scan accessList" style="width:100%"
                        data-id="{{$row->id}}"
                        data-val="nav">
                    <span class="{{$row->icon}}"></span> {{$row->name}}</button></td>
                <td>
                     <select class="form-control select2-default accessSelect"
                        data-id="{{$row->id}}"
                        data-val="nav">
                        <option value=""></option>
                        @foreach($levels as $level)
                            @if($level->id==$row->user_nav->level_id)
                            <option value="{{$level->id}}" selected>{{$level->name}}</option>
                        @else
                            <option value="{{$level->id}}">{{$level->name}}</option>
                        @endif                                                
                    @endforeach
                    </select>
                </td>
            </tr>
        @endforeach
    </table>
</div>