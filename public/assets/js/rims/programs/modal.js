$(document).on('click', '#programsDiv .programCodesModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/programCodesModal';
    var modal = 'default';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/programs/programCodesList',
        tid:'programCodesList',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#programCodesModal .programCodeNewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/programCodeNewModal';
    var modal = 'primary';
    var modal_size = 'modal-sm';
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
$(document).on('click', '#programCodesModal .programCodeEditModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/programCodeEditModal';
    var modal = 'primary';
    var modal_size = 'modal-sm';
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
$(document).on('click', '#curriculumDiv #curriculumTable .courseUpdate', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/courseUpdate';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/programs/courseTablePre',
        tid:'courseTablePre',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#programsDiv .viewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/viewModal';
    var modal = 'default';
    var modal_size = 'modal-xxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/programs/curriculumTable',
        tid:'curriculumTable',
        id:id,
        level:'All',
        status:'All'
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#curriculumModal #curriculumDiv button[name="newCourse"]', function (e) {
    var thisBtn = $(this);
    var id = $('#curriculumModal #curriculumDiv select[name="curriculum"] option:selected').val();
    var url = base_url+'/rims/programs/newCourse';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/programs/curriculumTablePre',
        tid:'curriculumTablePre',
        id:id,
        level:'All',
        status:'All'
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#programsDiv .programStatus', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/programStatusModal';
    var modal = 'primary';
    var modal_size = 'modal-sm';
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
$(document).on('click', '#curriculumModal button[name="curriculumNew"]', function (e) {
    var thisBtn = $(this);
    var id = $('#curriculumModal input[name="id"]').val();
    var url = base_url+'/rims/programs/curriculumNewModal';
    var modal = 'primary';
    var modal_size = 'modal-sm';
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
$(document).on('click', '#programsDiv .programNewModal', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/rims/programs/programNewModal';
    var modal = 'primary';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModal(form_data,thisBtn);
});