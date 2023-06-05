$(document).on('change', '#advisementDiv select[name="student"]', function (e) {
    student_info();
});
$(document).on('change', '#advisementDiv select[name="code"]', function (e) {
    curriculum_select();
});
$(document).on('change', '#courseAddModal select[name="program"]', function (e) {
    program_add_curriculum();
});
$(document).on('click', '#advisementDiv .courseCheck', function (e) {
    course_unit_total();
});
$(document).on('click', '#advisementDiv button[name="remove"]', function (e) {
    $(this).closest('tr').remove();
    var units = 0;
    $('#advisementDiv #courseAddedDiv .courseCheck:checked').each(function() {
        units += parseInt($(this).data('u'));
    });
    if(units<=0){
        $('#advisementDiv #courseAddedDiv').addClass('hide');
    }
    course_unit_total();
});
$(document).on('click', '#advisementDiv button[name="remove"]', function (e) {
    $(this).closest('tr').remove();
    var units = 0;
    $('#advisementDiv #courseAddedDiv .courseCheck:checked').each(function() {
        units += parseInt($(this).data('u'));
    });
    if(units<=0){
        $('#advisementDiv #courseAddedDiv').addClass('hide');
    }
    course_unit_total();
});
$(document).on('click', '#advisementDiv .year_check', function (e) {    
    var thisBtn = $(this);
    var val = thisBtn.val();    
    if (this.checked) {
        $('#advisementDiv .course_check'+val).prop('checked', true);
    }else{
        $('#advisementDiv .course_check'+val).prop('checked', false);
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