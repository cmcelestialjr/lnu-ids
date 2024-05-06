$(document).on('click', '#studentDiv #list .studentView', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    student_view(id,thisBtn);
});
$(document).on('click', '#studentDiv #new button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#studentDiv #new .studentSearch option:selected').val();
    student_view(id,thisBtn);
});
$(document).on('click', '#studentViewModal #tor', function (e) {
    var thisBtn = $(this);
    var id = $('#studentViewModal input[name="id"]').val();
    var program_level = $('#studentViewModal input[name="program_level"]').val();
    tor(id,program_level,thisBtn);
});
$(document).on('click', '#studentViewModal #curriculum', function (e) {
    var thisBtn = $(this);
    var id = $('#studentViewModal input[name="id"]').val();
    var program_level = $('#studentViewModal input[name="program_level"]').val();
    var curriculum = $('#studentViewModal input[name="curriculum"]').val();
    curriculumModal(id,program_level,curriculum,thisBtn);
});
$(document).on('click', '#studentViewModal .studentCoursesModal', function (e) {
    var thisBtn = $(this);
    var school_year_id = thisBtn.data('id');
    var id = $('#studentViewModal input[name="id"]').val();
    var url = base_url+'/rims/student/studentCoursesModal';
    var modal = 'primary';
    var modal_size = 'modal-xxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/student/studentCoursesTable',
        tid:'studentCoursesTable',
        id:id,
        school_year_id:school_year_id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#studentTORModal button[name="add"]', function (e) {
    var thisBtn = $(this);
    var id = $('#studentTORModal input[name="id"]').val();
    var level = $('#studentTORModal select[name="level"] option:selected').val();
    var url = base_url+'/rims/student/studentCourseAddModal';
    var modal = 'info';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        level:level
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#studentTORModal button[name="print"]', function (e) {
    var thisBtn = $(this);
    var id = $('#studentTORModal input[name="id"]').val();
    var level = $('#studentTORModal select[name="level"] option:selected').val();
    var url = base_url+'/rims/student/studentPrintModal';
    var modal = 'info';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        level:level
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#studentViewModal #shift', function (e) {
    var thisBtn = $(this);
    var id = $('#studentViewModal input[name="id"]').val();
    var url = base_url+'/rims/student/studentShiftModal';
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
$(document).off('click', '#studentViewModal .studentInfoEdit').on('click', '#studentViewModal .studentInfoEdit', function (e) {
    var thisBtn = $(this);
    var val = thisBtn.data('val');
    var array = ['Info','Contact','Educ','Fam'];

    if ($.inArray(val, array) !== -1) {

        var id = $('#studentViewModal input[name="id"]').val();
        var url = base_url+'/rims/student/studentEditInfo';
        var modal = 'info';
        var modal_size = 'modal-xl';
        var form_data = {
            url:url,
            modal:modal,
            modal_size:modal_size,
            static:'',
            w_table:'wo',
            id:id,
            val:val
        };
        loadModal(form_data,thisBtn);
    }
});
