
<div class="modal-content bg-primary" id="programsNewModal">
    <div class="modal-header">
        <h4 class="modal-title">
           <span class="fa fa-plus-square"></span> New Program
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
           <div class="col-lg-12">
                <label>Level</label>
                <select class="form-control select2-primary" name="level">
                    @foreach($levels as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
                <label>Department</label>
                <select class="form-control select2-primary" name="department">
                    @foreach($departments as $row)
                        <option value="{{$row->id}}">{{$row->name}} ({{$row->shorten}})</option>
                    @endforeach
                </select>
                <label>Name</label>
                <input type="text" class="form-control" name="name">
                <label>Shorten</label>
                <input type="text" class="form-control" name="shorten">
                <label>Code</label>
                <input type="text" class="form-control" name="code">
           </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
