view_schedule();
$(document).off('click', '#scheduleDiv #searchLI').on('click', '#scheduleDiv #searchLI', function (e) {
    view_search_div();
});
$(document).off('click', '#scheduleDiv #woLI').on('click', '#scheduleDiv #woLI', function (e) {
    sched_wo_table();
});
$(document).off('change', '#scheduleDiv #wo select').on('change', '#scheduleDiv #wo select', function (e) {
    sched_wo_table();
});
$(document).off('change', '#scheduleDiv select[name="school_year"]').on('change', '#scheduleDiv select[name="school_year"]', function (e) {
    view_schedule();
});
$(document).off('change', '#scheduleDiv #programsSelectDiv select[name="program"]').on('change', '#scheduleDiv #programsSelectDiv select[name="program"]', function (e) {
    view_by_program();
});
$(document).off('change', '#scheduleDiv #programsSelectDiv select[name="branch"]').on('change', '#scheduleDiv #programsSelectDiv select[name="branch"]', function (e) {
    view_by_program();
});
$(document).off('change', '#scheduleDiv #search #schedSearchDiv select[name="option_select"]').on('change', '#scheduleDiv #search #schedSearchDiv select[name="option_select"]', function (e) {
    sched_search_div();
});
$(document).off('change', '#scheduleDiv #search select[name="option"]').on('change', '#scheduleDiv #search select[name="option"]', function (e) {
    view_search_div();
});
$(document).off('change', '#courseSchedRmModal #schedule select[name="schedule"]').on('change', '#courseSchedRmModal #schedule select[name="schedule"]', function (e) {
    var thisBtn = $(this);
    var schedule = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').text();
    course_sched_rm_rm_instructor();
    course_sched_rm_table();
});
$(document).off('blur', '#courseSchedRmModal #schedule .timepicker-course').on('blur', '#courseSchedRmModal #schedule .timepicker-course', function (e) {
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