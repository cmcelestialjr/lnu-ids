<div class="modal-content">
    <div class="modal-header">
        <h4><span class="fa fa-info"></span> Amount: {{$docs->amount}}</h4>        
    </div>
    <div class="modal-body">
        <div class="card card-primary card-tabs">
            <div class="row">
                <div class="col-lg-12">
                    <iframe src="{{ asset($docs->doc) }}" 
                        style="width:100%; height:500px;" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>        
    </div>
</div>
<!-- /.modal-content -->
