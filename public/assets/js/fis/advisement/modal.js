$(document).on('click', '#advisementDiv #courseAddModal', function (e) {
    var thisBtn = $(this);
    var id = $('#advisementDiv select[name="student"] option:selected').val();
    if(id!=''){
        var id = $('#advisementDiv select[name="school_year"] option:selected').val();        
        var url = base_url+'/rims/enrollment/courseAddModal';
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
    }
});
$(document).on('click', '#advisementDiv .courseAnotherModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/enrollment/courseAnotherModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/enrollment/courseAnotherTable',
        tid:'courseAnotherTable',
        id:id
    };
    loadModal(form_data,thisBtn);
});