
<div class="modal-content">
    <div class="modal-header">
        <span class="fa fa-info"> Employer</span>
    </div>
    <div class="modal-body card card-primary card-outline">
        <div class="row">
            <div class="col-lg-12">
                Occupation: <label>{{$query->occupation}}</label>
            </div>
            <div class="col-lg-12">
                Employer: <label>{{$query->employer}}</label>
            </div>
            <div class="col-lg-12">
                Address: <label>{{$query->employer_address}}</label>
            </div>
            <div class="col-lg-12">
                Contact: <label>{{$query->employer_contact}}</label>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<!-- /.modal-content -->
