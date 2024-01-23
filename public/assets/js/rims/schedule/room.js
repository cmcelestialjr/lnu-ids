$(document).off('click', '#roomView').on('click', '#roomView', function (e) {    
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var room = $('#roomSelect option:selected').val();
    var schedule_id = $('#scheduleModal select[name="schedule"] option:selected').val();
    var url = base_url+'/rims/schedule/roomView';
    var modal = 'success';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        url_table:base_url+'/rims/schedule/roomTable',
        tid:'roomTable',
        id:id,
        room:room,
        schedule_id:schedule_id
    };
    loadModal(form_data,thisBtn);
});

$(document).off('change', '#roomModalSelect').on('change', '#roomModalSelect', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var room = thisBtn.val();
    var schedule_id = $('#scheduleModal select[name="schedule"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/schedule/roomTable',
        tid:'roomTable',
        id:id,
        room:room,
        schedule_id:schedule_id
    }; 
    loadDivwDisabled(form_data,thisBtn);
});

$(document).off('click', '#roomModalSubmit').on('click', '#roomModalSubmit', function (e) {
    var room = $('#roomModalSelect option:selected').val();
    var room_name = $('#roomModalSelect option:selected').text();
    $('#scheduleModal select[name="room"]').empty();
    $('#scheduleModal select[name="room"]').append('<option value="' + room + '">' + room_name + '</option>');
    $('#scheduleModal select[name="room"]').val(room).change();
    $('#modal-success').modal('hide');
});
