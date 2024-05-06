
<div class="modal-content" id="programEdit">
    <div class="modal-header">
        <h4 class="modal-title">
            {{$query->name}} ({{$query->shorten}})
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
           <div class="col-lg-12">
                <input type="hidden" name="id" value="{{$query->id}}">
                <label>Department</label>
                <select class="form-control select2-primary" name="department">
                    @foreach($department as $row)
                        @if($row->id==$query->department_id)
                            <option value="{{$row->id}}" selected>{{$row->shorten}}-{{$row->name}}</option>
                        @else
                            <option value="{{$row->id}}">{{$row->shorten}}-{{$row->name}}</option>
                         @endif 
                    @endforeach
                </select>
                <label>Unit</label>
                <div id="unitByDepartment">
                    <select class="form-control select2-primary unitByDepartment" name="unit">
                        <option value="">Please Select</option>
                        @foreach($unit as $row)
                            @if($row->id==$query->department_unit_id)
                                <option value="{{$row->id}}" selected>{{$row->name}}</option>
                            @else
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endif                            
                        @endforeach
                    </select>
                </div>
                <label>Name</label>
                <input type="text" class="form-control" name="name" value="{{$query->name}}">
                <label>Shorten</label>
                <input type="text" class="form-control" name="shorten" value="{{$query->shorten}}">
                <label>Code</label>
                <input type="text" class="form-control" name="code" value="{{$query->code}}">
                <label>Remarks</label>
                <textarea class="form-control" name="remarks">{{$query->remarks}}</textarea>
           </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->

