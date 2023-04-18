$(document).on('click', '#studentDiv #list .studentView', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
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