$(document).on('click', '#departmentsDiv .programsModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/departments/programsModal';
    var modal = 'default';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/departments/programsList',
        tid:'programsList',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#programsModal .programAddModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/departments/programAddModal';
    var modal = 'primary';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/departments/programAddList',
        tid:'programAddList',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#departmentsDiv .newModal', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/rims/departments/newModal';
    var modal = 'default';
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
$(document).on('click', '#departmentsDiv .editModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/departments/editModal';
    var modal = 'default';
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