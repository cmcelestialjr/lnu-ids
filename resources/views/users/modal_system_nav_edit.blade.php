<div class="modal-content bg-info" id="systemsNavEditDiv">
    <div class="modal-header">
        <h4 class="modal-title">{{$systems_nav->system->shorten}} / {{$systems_nav->name}}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">            
            <div class="col-md-12">
                <label>Name</label>
                <input type="text" class="form-control" name="name" value="{{$systems_nav->name}}">
                <label>Url</label>
                <input type="text" class="form-control" name="url" value="{{$systems_nav->url}}">
                <label>Icon</label>
                <input type="text" class="form-control" name="icon" value="{{$systems_nav->icon}}">
                <label>Order</label>
                <input type="text" class="form-control" name="order" value="{{$systems_nav->order}}">
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->