<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">System Access</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">                        
                        <table style="width:100%">
                            @foreach($systems as $row)
                                <tr>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->url}}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<!-- /.modal-content -->