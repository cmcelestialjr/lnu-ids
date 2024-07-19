
<div class="modal-content">
    <div class="modal-header">
        <h4><span class="fa fa-info"></span> {{date('F Y',strtotime($year.'-'.$month.'-01'))}}</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#hoursAccumulated" role="tab" aria-selected="true">Hours Accumulated</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#list" role="tab" aria-selected="true">DTR</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#list" role="tab" aria-selected="true">Payroll</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="hoursAccumulated" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label>Hours Accumulated</label>
                                        @if($payroll_month)
                                            <input type="number" class="form-control" value="{{$hour}}" readonly>
                                        @else
                                            <input type="number" class="form-control" value="{{$hour}}">
                                            <button class="btn btn-success btn-success-scan" style="width: 100%">
                                                <span class="fa fa-check"></span> Submit
                                            </button>
                                        @endif
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
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
    </div>
</div>
<!-- /.modal-content -->
