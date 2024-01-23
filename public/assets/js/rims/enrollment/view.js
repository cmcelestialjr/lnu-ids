date_list();
$(document).on('change', '#enrollmentDiv select[name="school_year"]', function (e) {
    var by = $('#enrollmentDiv select[name="by"] option:selected').val();
    if(by=='program'){
        enrollment();
    }else{
        date_list();
    }
    
});
$(document).on('change', '#enrollmentDiv select[name="by"]', function (e) {
    var val = $(this).val();
    $('#enrollmentDiv #dateDiv').removeClass('hide');
    if(val=='program'){
        $('#enrollmentDiv #dateDiv').addClass('hide');
        enrollment();
    }else{
        date_list();
    }    
});
$(document).on('change', '#enrollModal #studentInformationDiv select[name="program"]', function (e) {
    program_code();
});
$(document).on('change', '#enrollModal #studentInformationDiv #programCodeDiv select[name="program_code"]', function (e) {
    program_curriculum();
});
$(document).on('change', '#enrollModal #studentInformationDiv #programCurriculumDiv select[name="program_curriculum"]', function (e) {
    program_section();
});
$(document).on('change', '#enrollModal #studentInformationDiv #programSectionDiv select[name="program_section"]', function (e) {
    program_courses();
});
$(document).on('change', '#courseAddModal select[name="program"]', function (e) {
    program_add_curriculum();
});
$(document).on('change', '#enrollModal select[name="student"]', function (e) {
    student_information();
});
$(document).on('click', '#enrollModal #studentInformationDiv #programCoursesDiv .courseCheck', function (e) {
    course_unit_total();
});
$(document).on('change', '#enrollmentViewModal select', function (e) {
    student_list();
});
$(document).on('click', '#regIregLink', function (e) {
    regIreg_list();
});
$(document).on('change', '#regIreg select[name="type"]', function (e) {
    regIreg_list();
});
$(document).on('click', '#enrollModal #studentInformationDiv button[name="remove"]', function (e) {
    $(this).closest('tr').remove();
    var units = 0;
    $('#enrollModal #studentInformationDiv #courseAddedDiv .courseCheck:checked').each(function() {
        units += parseInt($(this).data('u'));
    });
    if(units<=0){
        $('#enrollModal #studentInformationDiv #courseAddedDiv').addClass('hide');
    }
    course_unit_total();
});
$(document).on('click', '#enrollModal #studentInformationDiv #programCoursesDiv .year_check', function (e) {    
    var thisBtn = $(this);
    var val = thisBtn.val();    
    if (this.checked) {
        $('#enrollModal #studentInformationDiv #programCoursesDiv .course_check'+val).prop('checked', true);
    }else{
        $('#enrollModal #studentInformationDiv #programCoursesDiv .course_check'+val).prop('checked', false);
    }
    course_unit_total();
});
$(document).on('click', '#courseAddModal #programAddCourseDiv .year_check', function (e) {    
    var thisBtn = $(this);
    var val = thisBtn.val();    
    if (this.checked) {
        $('#courseAddModal #programAddCourseDiv .course_check'+val).prop('checked', true);
    }else{
        $('#courseAddModal #programAddCourseDiv .course_check'+val).prop('checked', false);
    }
});