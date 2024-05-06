
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Schedule of {{$query->lastname}}, {{$query->firstname}}</h4>
    </div>
    <div class="modal-body" id="displayDiv">
    </div>
    <div class="modal-footer justify-content-between">
        <div id="employeeInformationModal">
            <input type="hidden" name="id_no" value="{{$query->id}}">
        </div>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<script src="{{ asset('assets/js/hrims/dtr/schedule.js') }}"></script>
<!-- /.modal-content -->
