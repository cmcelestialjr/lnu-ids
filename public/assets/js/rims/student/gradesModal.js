$(document).off('click', '#studentViewModal #grades').on('click', '#studentViewModal #grades', function (e) {
    var thisBtn = $(this);
    var id = $('#studentViewModal input[name="id"]').val();
    var url = base_url+'/rims/student/studentGradesModal';
    var modal = 'info';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id
    };
    loadModal(form_data,thisBtn);
});