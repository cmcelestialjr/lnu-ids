<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Systems Access</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body" id="usersAccessDiv">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-md-4">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                    <h3 class="card-title">
                        Systems
                    </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table style="width:100%">
                            @foreach($systems as $row)
                                <tr>
                                    <td  style="width:50%">
                                        <button class="btn btn-primary btn-primary-scan accessList" style="width:100%"
                                            data-id="{{$row->id}}"
                                            data-val="system">
                                        <span class="{{$row->icon}}"></span> {{$row->shorten}}</button></td>
                                    <td  style="width:50%">
                                        <select class="form-control accessSelect"
                                            data-id="{{$row->id}}"
                                            data-val="system">
                                            <option value=""></option>
                                            @foreach($levels as $level)
                                                @if($level->id==$row->user_system->level_id)
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
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-outline card-info" id="mainNavDiv">
                    <div class="card-header">
                        <h3 class="card-title">
                            Main Nav <b>({{$system_selected}})</b>
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
                                        <select class="form-control accessSelect"
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
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-outline card-warning" id="subNavDiv">
                    <div class="card-header">
                    <h3 class="card-title">
                        Sub Nav <b>{{$systems_nav_selected}}</b>
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
                                    <td>
                                        <select class="form-control accessSelect"
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
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<!-- /.modal-content -->