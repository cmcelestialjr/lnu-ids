pre_enroll_div();
$(document).on('change', '#courseAddModal select[name="program"]', function (e) {
    program_add_curriculum();
});
$(document).on('click', '#preEnrollDiv button[name="remove"]', function (e) {
    $(this).closest('tr').remove();
    var units = 0;
    $('#preEnrollDiv #courseAddedDiv .courseCheck:checked').each(function() {
        units += parseInt($(this).data('u'));
    });
    if(units<=0){
        $('#preEnrollDiv #courseAddedDiv').addClass('hide');
    }
    course_unit_total();
});