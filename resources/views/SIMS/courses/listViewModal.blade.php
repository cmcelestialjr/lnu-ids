
<div class="modal-content" id="listViewModal">
    <div class="modal-header">
        <h4 class="modal-title"><span class="fa fa-info"></span> {{$course['course_code']}}</h4>
        <span class="fa fa-times btn-no-design" data-dismiss="modal">x</span>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12"><br>            
                <table class="table table-bordered">
                    <tr>
                        <td style="width: 20%;"><b>Description:</b></td>
                        <td style="width: 80%;" colspan="3"> {{$course['course_desc']}}</td>
                    </tr>
                    <tr>
                        <td style="width: 10%;"><b>Units:</b></td>
                        <td style="width: 40%;"> {{$course['course_units']}}</td>
                        <td style="width: 10%;"><b>Lab:</b></td>
                        <td style="width: 40%;"> {{$course['lab_units']}}</td>
                    </tr>
                    @if($course['school_year_id']==NULL)
                        <tr>
                            <td><b>School:</b></td>
                            <td colspan="3"> {{$course['school_name']}}</td>
                        </tr>
                        <tr>
                            <td><b>Program:</b></td>
                            <td colspan="3"> {{$course['program_shorten']}} - {{$course['program_name']}}</td>
                        </tr>
                    @else
                        <tr>
                            <td><b>Schedule:</b></td>
                            <td colspan="3"> {{$course['schedule']}}</td>
                        </tr>
                        <tr>
                            <td><b>Teacher/Instructor:</b></td>
                            <td colspan="3"> {{$course['instructor_name']}}</td>
                        </tr>
                        <tr>
                            <td><b>Room:</b></td>
                            <td colspan="3"> {{$course['room_name']}}</td>
                        </tr>
                    @endif
                    @if($course['credit_course_id'])
                        <tr>
                            <td><b>Credited to: </b></td>
                            <td colspan="3"> {{$course['course_credit_code']}}</td>
                        </tr>
                        <tr>
                            <td><b>Credited by:</b></td>
                            <td colspan="3"> {{$course['course_credit_by']}}</td>
                        </tr>
                        <tr>
                            <td><b>Credited at:</b></td>
                            <td colspan="3"> {{$course['credited_at']}}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<!-- /.modal-content -->