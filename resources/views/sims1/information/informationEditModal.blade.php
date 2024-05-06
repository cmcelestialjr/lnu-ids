
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title"> <span class="fa fa-edit"></span></h4>
        <span class="fa fa-times btn-no-design" data-dismiss="modal">x</span>
    </div>
    <div class="modal-body">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12" id="informationEditDiv">
                        <div class="card card-primary card-tabs">
                            <div class="card-header p-0 pt-1">
                              <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                  <a class="nav-link active" data-toggle="pill" href="#" role="tab" aria-selected="true">Personal Info</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" data-toggle="pill" href="#" role="tab" aria-selected="false">Educational Background</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="pill" href="#" role="tab" aria-selected="false">Family Background</a>
                                  </li>
                              </ul>
                            </div>
                            <div class="card-body">
                              <div class="tab-content">
                                <div class="tab-pane fade show active" role="tabpanel">
                                    <table class="table">
                                        <tr>
                                            <td style="width:5%"><input type="text" class="form-control"></td>
                                            <td style="width:30%"><input type="text" class="form-control"></td>
                                            <td style="width:10%"><input type="text" class="form-control"></td>
                                            <td style="width:20%"><input type="text" class="form-control"></td>
                                            <td style="width:15%"><input type="text" class="form-control"></td>
                                            <td style="width:5%"><input type="text" class="form-control"></td>
                                            <td style="width:15%"><input type="text" class="form-control"></td>
                                        </tr>
                                        @for($i=0;$i<10;$i++)
                                        <tr>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                        </tr>
                                        @endfor
                                    </table>
                                </div>
                              </div>
                            </div>
                        </div>
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
