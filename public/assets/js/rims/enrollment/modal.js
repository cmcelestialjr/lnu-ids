$(document).on('click', '#enrollmentDiv button[name="enroll"]', function (e) {
    var thisBtn = $(this);
    var id = $('#enrollmentDiv select[name="school_year"] option:selected').val();
    var url = base_url+'/rims/enrollment/enrollModal';
    var modal = 'default';
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
$(document).on('click', '#enrollModal #studentInformationDiv #programCoursesDiv .courseAnotherModal', function (e) {
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
$(document).on('click', '#enrollModal #courseAddModal', function (e) {
    var thisBtn = $(this);
    var id = $('#enrollModal #students select[name="student"] option:selected').val();
    if(id!=''){
        var id = $('#enrollmentDiv select[name="school_year"] option:selected').val();        
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
$(document).on('click', '.enrollmentViewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/enrollment/enrollmentViewModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/enrollment/enrollmentViewTable',
        tid:'enrollmentViewTable',
        id:id,
        curriculum:'',
        section:''
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '.coursesViewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var spid = thisBtn.data('spid');
    var school_year_id = $('#enrollmentDiv select[name="school_year"] option:selected').val();
    var url = base_url+'/rims/enrollment/coursesViewModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/enrollment/coursesViewTable',
        tid:'coursesViewTable',
        id:id,
        spid:spid,
        school_year_id:school_year_id
    };
    loadModal(form_data,thisBtn);
});