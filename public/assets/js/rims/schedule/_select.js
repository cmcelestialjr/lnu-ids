$(document).ready(function() {
    var course_id = $('#courseSchedRmModal input[name="id"]').val();
    var instructor_id = $('#courseSchedRmModal #rm_instructor select[name="instructor"] option:selected').val();
    var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();
    var room_id = $('#courseSchedRmModal #rm_instructor select[name="room"] option:selected').val();
    $('#courseSchedRmModal #rm_instructor select[name="room"]').select2({
        dropdownParent: $("#rm_instructor"),
        ajax: { 
        url: base_url+'/rims/schedule/selectRoom',
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                _token: CSRF_TOKEN,
                course_id:course_id,
                instructor_id:instructor_id,
                schedule_id:schedule_id,
                room_id:room_id,
                search: params.term
            };
        },
        processResults: function (response) {
            return {
            results: response
            };
        },
        cache: true
        }
    });
    $('#courseSchedRmModal #rm_instructor select[name="instructor"]').select2({
        dropdownParent: $("#rm_instructor"),
        ajax: { 
        url: base_url+'/rims/schedule/selectInstructor',
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                _token: CSRF_TOKEN,
                course_id:course_id,
                instructor_id:instructor_id,
                schedule_id:schedule_id,
                room_id:room_id,
                search: params.term
            };
        },
        processResults: function (response) {
            return {
            results: response
            };
        },
        cache: true
        }
    });
});