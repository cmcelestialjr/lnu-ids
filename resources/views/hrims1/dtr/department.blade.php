<div class="modal-content" id="departmentModal">
    <div class="modal-header">
        <h4 class="modal-title">
           {{$query->lastname}}, {{$query->firstname}} - {{$query->id_no}}
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-info card-outline">
                    <div class="card-body table-responsive">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>Department</label>
                                <select class="form-control select2-primary" name="department">
                                    <option value="">Please Select...</option>
                                    @foreach($departments as $row)
                                        @if($user_role->department_id==$row->id)
                                            <option value="{{$row->id}}" selected>{{$row->name}} - ({{$row->shorten}})</option>
                                        @else
                                            <option value="{{$row->id}}">{{$row->name}} - ({{$row->shorten}})</option>
                                        @endif                                        
                                    @endforeach
                                </select><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
