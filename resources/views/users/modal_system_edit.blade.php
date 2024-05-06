<div class="modal-content" id="systemsEditDiv">
    <div class="modal-header">
        <h4 class="modal-title">{{$query->shorten}}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-md-12">
                <label>Name</label>
                <input type="text" class="form-control" name="name" value="{{$query->name}}">
                <label>Shorten</label>
                <input type="text" class="form-control" name="shorten" value="{{$query->shorten}}">
                <label>Icon</label>
                <input type="text" class="form-control" name="icon" value="{{$query->icon}}">
                <label>Button</label>
                <input type="text" class="form-control" name="button" value="{{$query->button}}">
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->