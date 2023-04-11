view_schedule();
$(document).on('click', '#scheduleDiv #searchLI', function (e) {
    view_search_div();
});
$(document).on('click', '#scheduleDiv #woLI', function (e) {
    sched_wo_table();
});
$(document).on('change', '#scheduleDiv #wo select[name="option"]', function (e) {
    sched_wo_table();
});
$(document).on('change', '#scheduleDiv select[name="school_year"]', function (e) {
    view_schedule();
});
$(document).on('change', '#scheduleDiv #programsSelectDiv select[name="program"]', function (e) {
    view_by_program();
});
$(document).on('change', '#scheduleDiv #search #schedSearchDiv select[name="option_select"]', function (e) {
    sched_search_div();
});
$(document).on('change', '#scheduleDiv #search #schedSearchDiv select[name="option_select"]', function (e) {
    sched_search_div();
});
$(document).on('change', '#scheduleDiv #search select[name="option"]', function (e) {
    view_search_div();
});
$(document).on('change', '#courseSchedRmModal #schedule select[name="schedule"]', function (e) {
    var thisBtn = $(this);
    var schedule = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').text();
    if(schedule=='New'){
        $('#courseSchedRmModal #schedule input[name="time_from"]').val('07:30am');
        $('#courseSchedRmModal #schedule input[name="time_to"]').val('09:00am');
    }else{
        console.log(schedule);
        var sched = schedule.split('-');
        $('#courseSchedRmModal #schedule input[name="time_from"]').val(sched[0]);
        $('#courseSchedRmModal #schedule input[name="time_to"]').val(sched[1]);
    }
    course_sched_rm_rm_instructor();
    course_sched_rm_table();
});
$(document).on('blur', '#courseSchedRmModal #schedule .timepicker-course', function (e) {
    var time_from = $('#courseSchedRmModal #schedule input[name="time_from"]').val();
    var time_to = $('#courseSchedRmModal #schedule input[name="time_to"]').val();
    var time_from_valid = moment(time_from, "hh:mma", true).isValid();
    var time_to_valid = moment(time_to, "hh:mma", true).isValid();
    $('#courseSchedRmModal #schedule input[name="time_from"]').removeClass('border-require');
    $('#courseSchedRmModal #schedule input[name="time_to"]').removeClass('border-require');
    if(time_from_valid==false){
        $('#courseSchedRmModal #schedule input[name="time_from"]').addClass('border-require');
        toastr.error('Invalid time format in Time From');
    }
    if(time_to_valid==false){
        $('#courseSchedRmModal #schedule input[name="time_to"]').addClass('border-require');
        toastr.error('Invalid time format in Time To');
    }
});