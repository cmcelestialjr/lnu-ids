<div class="card-header">
    <h3 class="card-title">
        Sub Nav <b>({{$system_selected->name}})</b>
    </h3>
</div>
<!-- /.card-header -->
<div class="card-body">
    <table style="width:100%">
        @foreach($systems_nav_sub as $row)
            <tr>
                <td  style="width:50%">
                    <button class="btn btn-warning btn-warning-scan" style="width:100%">
                    <span class="{{$row->icon}}"></span> {{$row->name}}</button></td>
                <td>
                     <select class="form-control select2-default accessSelect"
                        data-id="{{$row->id}}"
                        data-val="nav_sub">
                        <option value=""></option>
                        @foreach($levels as $level)
                            @if($level->id==$row->user_nav_sub->level_id)
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