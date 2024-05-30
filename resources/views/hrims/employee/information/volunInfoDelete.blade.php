
<div class="modal-content" id="volun-delete-modal">
    <div class="modal-header">
        <span class="fa fa-trash"></span>
    </div>
    <div class="modal-body card card-danger card-outline">
        <div class="row">
            <div class="col-lg-12 center">
                <label>
                    Are you really sure you want to remove your
                    {{$query->name}}<br>
                    Dated {{date('m/d/Y',strtotime($query->date_from))}}<br>
                    in the list??
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger btn-danger-scan" id="submit" data-id="{{$query->id}}"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
