
<div class="modal-content" id="employeeInformationModal">
    <div class="modal-header">
    </div>
    <div class="modal-body">
        <div class="row table-responsive">
            <div class="col-lg-12">
                <div class="alert alert-info">
                    <h4>Information of <b>
                        {{$name}}
                    </b>
                    </h4>
                    <div style="float:right;"></div>
                </div>
            </div>
            <div class="modal-body table-responsive">
                <div class="row">
                    <div class="col-md-3">
                        <br>
                        <input type="hidden" value="{{$query->id}}" name="id_no">
                        <button class="btn btn-primary btn-primary-scan btn-primary-scan-active buttonDisp"
                            data-t="info"
                             style="width:100%">Personal Information</button>
                        <br><br>
                        <button class="btn btn-primary btn-primary-scan buttonDisp"
                            data-t="offices"
                             style="width:100%">Division/Section</button>
                        <br><br>
                        <button class="btn btn-primary btn-primary-scan buttonDisp"
                            data-t="fam"
                             style="width:100%">Family Background</button>
                        <br><br>
                        <button class="btn btn-primary btn-primary-scan buttonDisp"
                            data-t="educ"
                             style="width:100%">Educational Background</button>
                        <br><br>
                        <button class="btn btn-primary btn-primary-scan buttonDisp"
                            data-t="elig"
                             style="width:100%">Eligibility</button>
                        <br><br>
                        <button class="btn btn-primary btn-primary-scan  buttonDisp"
                            data-t="exp"
                             style="width:100%">Work Experience</button>
                        <br><br>
                        <button class="btn btn-primary btn-primary-scan buttonDisp"
                            data-t="volun"
                             style="width:100%">Voluntary Work</button>
                        <br><br>
                        <button class="btn btn-primary btn-primary-scan buttonDisp"
                            data-t="train"
                             style="width:100%">Trainings</button>
                        <br><br>
                        <button class="btn btn-primary btn-primary-scan buttonDisp"
                            data-t="others"
                             style="width:100%">Other Information</button>
                        <br><br>
                        <button class="btn btn-primary btn-primary-scan buttonDisp"
                            data-t="doc"
                             style="width:100%">Documents</button>
                        <br><br>
                        <button class="btn btn-primary btn-primary-scan buttonDisp"
                            data-t="sched"
                             style="width:100%">Schedule</button>
                        <br><br><br><br>
                    </div>
                    {{-- <div class="col-md-9 callout callout-info hide" id="loader_div" style="background-color:#f4f0ec">
                            <img src="{{ asset('/assets/images/loader/loader_gif_no_bg.gif') }}" 
                                style="height: 100%;
                                        position: absolute;
                                        top: 40%;
                                        left: 50%;
                                        transform: translate(-50%, -50%);">
                    </div> --}}
                    <div class="col-md-9 callout callout-info" id="displayDiv">

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
