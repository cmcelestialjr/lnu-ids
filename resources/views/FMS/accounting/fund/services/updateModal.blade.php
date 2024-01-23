
<div class="modal-content" id="servicesUpdateModal">
    <input type="hidden" name="id" value="{{$query->id}}">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> Update Fund Services</h4>
    </div>
    <div class="modal-body table-responsive">
        <span class="text-require">*</span> Required fields
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Name<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{$query->name}}">  
                        <label>Shorten<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="shorten" value="{{$query->shorten}}">                      
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-plus"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
