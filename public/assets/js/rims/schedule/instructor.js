$(document).off('click', '#instructorView').on('click', '#instructorView', function (e) {    
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var instructor = $('#scheduleModal select[name="instructor"] option:selected').val();
    var schedule_id = $('#scheduleModal select[name="schedule"] option:selected').val();
    var url = base_url+'/rims/schedule/instructorView';
    var modal = 'success';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        url_table:base_url+'/rims/schedule/instructorTable',
        tid:'instructorTable',
        id:id,
        instructor:instructor,
        schedule_id:schedule_id
    };
    loadModal(form_data,thisBtn);
});

$(document).off('change', '#instructorModalSelect').on('change', '#instructorModalSelect', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var instructor = thisBtn.val();
    var schedule_id = $('#scheduleModal select[name="schedule"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/schedule/instructorTable',
        tid:'instructorTable',
        id:id,
        instructor:instructor,
        schedule_id:schedule_id
    }; 
    loadDivwDisabled(form_data,thisBtn);
});

$(document).off('click', '#instructorModalSubmit').on('click', '#instructorModalSubmit', function (e) {
    var instructor = $('#instructorModalSelect option:selected').val();
    var instructor_name = $('#instructorModalSelect option:selected').text();
    $('#scheduleModal select[name="instructor"]').empty();
    $('#scheduleModal select[name="instructor"]').append('<option value="' + instructor + '">' + instructor_name + '</option>');
    $('#scheduleModal select[name="instructor"]').val(instructor).change();
    $('#modal-success').modal('hide');
});
