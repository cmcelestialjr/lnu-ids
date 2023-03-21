
<div class="modal-content bg-info" id="programCodeEditModal">
    <div class="modal-header">
        <h4 class="modal-title">
            <span class="fa fa-edit"></span> Edit Code
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
           <div class="col-lg-12">
                <input type="hidden" name="id" value="{{$id}}">
                <label>Code</label>
                <input type="text" class="form-control" name="name" value="{{$query->name}}">
                <label>Remarks</label>
                <textarea class="form-control" name="remarks">{{$query->remarks}}</textarea>
           </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
