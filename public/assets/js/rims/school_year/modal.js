$(document).on('click', '#schoolYearDiv .schoolYearEdit', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/schoolYear/editView';
    var modal = 'default';
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
$(document).on('click', '#schoolYearDiv .programsViewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/schoolYear/programsViewModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/schoolYear/programsViewTable',
        tid:'programsViewTable',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#programsViewModal .coursesOpenModal', function (e) {
    var thisBtn = $(this);
    var id = $('#programsViewModal input[name="id"]').val();
    var url = base_url+'/rims/schoolYear/coursesOpenModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
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
$(document).on('click', '#programsViewModal .coursesViewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/schoolYear/coursesViewModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/schoolYear/curriculumViewList',
        tid:'curriculumViewList',
        id:id,
        type:'modal'
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#coursesViewModal #curriculumViewList .courseStatusModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/schoolYear/courseViewStatusModal';
    var modal = 'info';
    var modal_size = 'modal-md';
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