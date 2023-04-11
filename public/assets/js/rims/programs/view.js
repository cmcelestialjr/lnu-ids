view_programs();
$(document).on('change', '#programsDiv select[name="status"]', function (e) {
    view_programs();
});
$(document).on('change', '#curriculumModal #curriculumDiv .curriculumSelects', function (e) {
    var thisBtn = $(this);
    curriculum_div(thisBtn);
});
$(document).on('click', '#newCourseModal .all', function (e) {
    if (this.checked) {
        $('#newCourseModal .courses').prop('checked', true);
        $('#newCourseModal input[name="pre_name"]').val('All');
    }else{
        $('#newCourseModal .courses').prop('checked', false);
        $('#newCourseModal input[name="pre_name"]').val('None');
    }
});
$(document).on('click', '#newCourseModal .courses', function (e) {
    var courses = [];
    $('#newCourseModal .courses:checked').each(function() {
        courses.push($(this).data('val'));
    });
    if(courses.length==0){
        var courses = 'None';
    }
    $('#newCourseModal input[name="pre_name"]').val(courses);
});
$(document).on('click', '#courseUpdateModal .all', function (e) {
    if (this.checked) {
        $('#courseUpdateModal .courses').prop('checked', true);
        $('#courseUpdateModal input[name="pre_name"]').val('All');
    }else{
        $('#courseUpdateModal .courses').prop('checked', false);
        $('#courseUpdateModal input[name="pre_name"]').val('None');
    }
});
$(document).on('click', '#courseUpdateModal .courses', function (e) {
    var courses = [];
    $('#courseUpdateModal .courses:checked').each(function() {
        courses.push($(this).data('val'));
    });
    if(courses.length==0){
        var courses = 'None';
    }
    $('#courseUpdateModal input[name="pre_name"]').val(courses);
});
$(document).on('input', '#newCourseModal .req', function (e) {
    var code = $('#newCourseModal input[name="code"]').val();
    var name = $('#newCourseModal input[name="name"]').val();
    var units = $('#newCourseModal input[name="units"]').val();
    var pre_name = $('#newCourseModal input[name="pre_name"]').val();
    var lab = $('#newCourseModal input[name="pre_name"]').val();
    $('#newCourseModal input[name="code"]').removeClass('border-require');
    $('#newCourseModal input[name="name"]').removeClass('border-require');
    $('#newCourseModal input[name="units"]').removeClass('border-require');
    $('#newCourseModal input[name="lab"]').removeClass('border-require');
    if(code==''){
        $('#newCourseModal input[name="code"]').addClass('border-require');
    }
    if(name==''){
        $('#newCourseModal input[name="name"]').addClass('border-require');
    }
    if(units=='' || units<=0){
        $('#newCourseModal input[name="units"]').addClass('border-require');
    }
    if(pre_name==''){
        $('#newCourseModal input[name="pre_name"]').addClass('border-require');
    }
    if(lab==''){
        $('#newCourseModal input[name="lab"]').addClass('border-require');
    }
});
$(document).on('input', '#programsNewModal', function (e) {
    var name = $('#programsNewModal input[name="name"]').val();
    var shorten = $('#programsNewModal input[name="shorten"]').val();
    var code = $('#programsNewModal input[name="code"]').val();
    $('#programsNewModal input[name="name"]').removeClass('border-require');
    $('#programsNewModal input[name="shorten"]').removeClass('border-require');
    $('#programsNewModal input[name="code"]').removeClass('border-require');
    if(name==''){
        $('#programsNewModal input[name="name"]').addClass('border-require');
    }
    if(shorten==''){
        $('#programsNewModal input[name="shorten"]').addClass('border-require');
    }
    if(code==''){
        $('#programsNewModal input[name="code"]').addClass('border-require');
    }
});