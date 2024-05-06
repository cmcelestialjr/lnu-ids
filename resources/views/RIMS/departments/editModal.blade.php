
<div class="modal-content bg-info" id="editModal">
    <div class="modal-header">
        <h4 class="modal-title">
           <span class="fa fa-edit"></span> Edit
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
           <div class="col-lg-12">
                <input type="hidden" name="id" value="{{$department->id}}">
                <label>Name</label>
                <input type="text" class="form-control" name="name" value="{{$department->name}}">
                <label>Shorten</label>
                <input type="text" class="form-control" name="shorten" value="{{$department->shorten}}">
                <label>Code</label>
                <input type="text" class="form-control" name="code" value="{{$department->code}}">
           </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
