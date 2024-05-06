
<div class="modal-content bg-{{$class}}" id="programStatusModal">
    <div class="modal-header">
        <h4 class="modal-title">
            {{$query->name}}
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$query->id}}">
            <div class="col-md-12">
                <h4 class="center">Are you really sure you want to change the status to {{$status}}??</h4>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> No</button>
        <button type="button" class="btn btn-{{$btn}} btn-{{$btn}}-scan" name="submit"><span class="fa fa-check"></span> Yes</button>
    </div>
</div>
<!-- /.modal-content -->
