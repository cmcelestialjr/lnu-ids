$(document).on('click', '#studentsDiv #list .studentView', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    student_view(id,thisBtn);
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
    var modal = 'default';
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
$(document).on('click', '#subjectsDiv .studentsListModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/fis/subjects/studentsListModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/fis/subjects/studentsListTable',
        tid:'studentsListTable',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#studentsListModal .studentGradeModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var sid = thisBtn.data('sid');
    var url = base_url+'/fis/subjects/studentGradeModal';
    var modal = 'primary';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        sid:sid
    };
    loadModal(form_data,thisBtn);
});