<div class="card card-info card-outline">
    <div class="card-body table-responsive">
        <table class="table table-bordered">
            <thead>
                <th>Course Code</th>
                <th>Section</th>
                <th>Schedule</th>
                <th>Room</th>
                <th>Instructor</th>
                <th><input type="checkbox" class="form-control" name="all"></th>
            </thead>
            <tbody>
                @foreach($coursesList as $r)
                    <tr>
                        <td class="center">{{$r['code']}}</td>
                        <td class="center">{{$r['section']}}</td>
                        <td class="center">{!!$r['schedule']!!}</td>
                        <td class="center">{!!$r['room']!!}</td>
                        <td class="center">{{$r['instructor']}}</td>
                        <td class="center">
                            @if($r['status']==NULL)
                                @if($addDropStatus==1)
                                <input type="checkbox" class="form-control coursesList" value="{{$r['id']}}">
                                @endif
                            @else
                                @if($r['option']==1)
                                    <button class="btn btn-success btn-success-scan btn-xs">
                                        {{$r['status']}}
                                    </button>
                                @else
                                    <button class="btn btn-danger btn-danger-scan btn-xs">
                                        {{$r['status']}}
                                    </button>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table><br>
        @if($addDropStatus==1)
        <button class="btn btn-success btn-success-scan" name="drop_submit" style="width:100%">
            <span class="fa fa-check"></span> Submit Drop
        </button>
        @endif
    </div>
</div>