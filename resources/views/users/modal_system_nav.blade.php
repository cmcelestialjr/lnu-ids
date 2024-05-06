<div class="modal-content" id="systemsNavDiv">
    <div class="modal-header">
        <h4 class="modal-title">{{$query->shorten}}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">      
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            Main Nav <button class="btn btn-primary btn-primary-scan" id="new"
                                        data-id="{{$id}}">
                                        <span class="fa fa-plus"></span> New</button>
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table style="width:100%">
                            <tr>
                                <th><center>Nav</center></th>
                                <th><center>Url</center></th>
                                <th><center>Order</center></th>
                                <th><center>Option</center></th>
                            </tr>
                            @foreach($systems_nav as $row)
                                <tr>
                                    <td style="width:25%">
                                        <button class="btn btn-info btn-info-scan" style="width:100%">
                                            <span class="{{$row->icon}}"></span> {{$row->name}}</button></td>
                                    <td style="width:25%">
                                        <center>{{$row->url}}</center>
                                    </td>
                                    <td style="width:25%">
                                        <center>{{$row->order}}</center>
                                    </td>
                                    <td style="width:25%">
                                        <button class="btn btn-primary btn-primary-scan navSub"
                                            data-id="{{$row->id}}">
                                            <span class="fa fa-edit"></span> Nav Sub</button>
                                        <button class="btn btn-info btn-info-scan edit"
                                            data-id="{{$row->id}}">
                                            <span class="fa fa-edit"></span> Edit</button>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<!-- /.modal-content -->