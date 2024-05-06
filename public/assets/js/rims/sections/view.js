view_sections();
$(document).on('change', '#sectionDiv select[name="school_year"]', function (e) {
    view_sections();
});
$(document).on('change', '#sectionDiv #programsSelectDiv .select2-programsSelect', function (e) {
    view_sections_by_program();
});
$(document).on('change', '#sectionNewModal select[name="curriculum"]', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/sections/gradeLevelSelect',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('input-loading');
            $('#sectionNewModal select[name="grade_level"]').attr('disabled','disabled');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading');
            $('#sectionNewModal select[name="grade_level"]').removeAttr('disabled');
            if(data=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');
            }else{
                toastr.success('Success');
                $('#sectionNewModal #gradeLevelDiv').html(data);
                $(".select2-gradeLevelSelect").select2({
                    dropdownParent: $("#sectionNewModal #gradeLevelDiv")
                });
            }
            setTimeout(function() {
                thisBtn.removeClass('input-success');
                thisBtn.removeClass('input-error');
            }, 3000);
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
});
$(document).on('change', '#courseSchedRmModal #schedule select[name="schedule"]', function (e) {
    var thisBtn = $(this);
    var schedule = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').text();
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
