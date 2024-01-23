$(document).on('click', '#courseSchedRmModal #courseSchedRmTable .scheduleTimeUpdate', function (e) {
    var thisB = $(this);
    var thisBtn = $('#courseSchedRmModal #courseSchedRmTable .scheduleTimeUpdate');
    var id = $('#courseSchedRmModal input[name="id"]').val();
    var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();
    var hour_no = $('#courseSchedRmModal #schedule select[name="hour_no"]').val();
    var type = $('#courseSchedRmModal #rm_instructor input[name="type"]:checked').val();
    var time_from = $('#courseSchedRmModal #schedule input[name="time_from"]').val();
    var time_to = $('#courseSchedRmModal #schedule input[name="time_to"]').val();
    var t = $(this).data('t');
    var d = $(this).data('d');
    var x = 0;
    var time_from_valid = moment(time_from, "hh:mma", true).isValid();
    var time_to_valid = moment(time_to, "hh:mma", true).isValid();
    $('#courseSchedRmModal #schedule input[name="time_from"]').removeClass('border-require');
    $('#courseSchedRmModal #schedule input[name="time_to"]').removeClass('border-require');
    if(time_from_valid==false){
        $('#courseSchedRmModal #schedule input[name="time_from"]').addClass('border-require');
        toastr.error('Invalid time format in Time From');
        x++;
    }
    if(time_to_valid==false){
        $('#courseSchedRmModal #schedule input[name="time_to"]').addClass('border-require');
        toastr.error('Invalid time format in Time To');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            t:t,
            d:d,
            schedule_id:schedule_id,
            hour_no:hour_no,
            type:type,
            time_from:time_from,
            time_to:time_to        
        };
        $.ajax({
            url: base_url+'/rims/sections/scheduleTimeUpdate',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                thisBtn.attr('disabled','disabled');
                thisB.removeClass('btn-no-design');
                thisB.removeClass('bg-info');
                thisB.removeClass('bg-lightgreen');            
                thisB.removeClass('bg-primary');
                thisB.removeClass('bg-secondary');
                thisB.removeClass('bg-warning');
                thisB.removeClass('bg-danger');
                thisB.addClass('bg-success');
                thisB.addClass('input-loading');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisB.removeClass('input-loading');
                if(data.result=='success'){
                    toastr.success('Success');
                    thisB.addClass('input-success');
                    $('#courseSchedRmModal #courseSchedRmTable .blank').html('&nbsp;&nbsp;');
                    $('#courseSchedRmModal #schedule select[name="schedule"] option[value="' + data.sched_id + '"]').remove();
                    $('#courseSchedRmModal #schedule select[name="schedule"]').append($('<option value="'+data.sched_id+'" selected>'+data.sched_name+'</option>'));
                    $('#courseSchedRmModal #courseSchedRmTable #scheduleRemoveDayTr').removeClass('hide');  
                    $('#courseSchedRmModal #courseSchedRmTable .bg-success').html('&nbsp;&nbsp;');
                    $('#courseSchedRmModal #courseSchedRmTable .bg-success').addClass('blank');
                    $('#courseSchedRmModal #courseSchedRmTable .bg-success').removeClass('bg-success');
                    $.each(data.list_x, function(index, val) {
                        var split = val.split("_");
                        $('#courseSchedRmModal #courseSchedRmTable #dayTime'+split[0]).removeClass('btn-no-design');
                        $('#courseSchedRmModal #courseSchedRmTable #dayTime'+split[0]).addClass('bg-success btn-no-design');
                        $('#courseSchedRmModal #courseSchedRmTable #dayTime'+split[0]).html(split[1]);
                    });
                    course_sched_rm_details();
                    course_sched_rm_rm_instructor();
                }else if(data.result=='conflict'){
                    toastr.error('Conflict schedule please check carefully.');
                    thisB.removeClass('bg-success');
                    thisB.addClass('input-error'); 
                }else if(data.result=='time'){
                    toastr.error('Time From must less than Time To.');
                    $('#courseSchedRmModal #schedule input[name="time_from"]').addClass('border-require');
                    $('#courseSchedRmModal #schedule input[name="time_to"]').addClass('border-require');
                    thisB.removeClass('bg-success');
                    thisB.addClass('input-error'); 
                }else if(data.result=='school_from'){
                    toastr.error('Time From must be greater than or equal to default time '+data.time_from);
                    $('#courseSchedRmModal #schedule input[name="time_from"]').addClass('border-require');
                    thisB.removeClass('bg-success');
                    thisB.addClass('input-error'); 
                }else if(data.result=='school_to'){
                    toastr.error('Time To must be less than or equal to default time '+data.time_to);
                    $('#courseSchedRmModal #schedule input[name="time_to"]').addClass('border-require');
                    thisB.removeClass('bg-success');
                    thisB.addClass('input-error');
                }else{
                    toastr.error('Error.');
                    thisB.addClass('input-error'); 
                }
                setTimeout(function() {
                    thisB.removeClass('input-success');
                    thisB.removeClass('input-error');
                }, 3000);
            },
            error: function (){
                toastr.error('Error!');
                thisBtn.removeAttr('disabled');
                thisB.removeClass('input-success');
                thisB.removeClass('input-error');
            }
        });
    }
});
$(document).on('change', '#courseSchedRmModal #rm_instructor .select2-rm_instructor', function (e) {
    var thisBtn = $('#courseSchedRmModal #rm_instructor .select2-rm_instructor');
    var id = $('#courseSchedRmModal input[name="id"]').val();
    var instructor_id = $('#courseSchedRmModal #rm_instructor select[name="instructor"] option:selected').val();
    var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();
    var room_id = $('#courseSchedRmModal #rm_instructor select[name="room"] option:selected').val();
    var form_data = {
        id:id,
        instructor_id:instructor_id,
        room_id:room_id,
        schedule_id:schedule_id
    };
    $.ajax({
        url: base_url+'/rims/sections/courseSchedRmInstructorUpdate',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            if(data.result=='success'){
                toastr.success('Success');
                thisBtn.addClass('input-success');
                course_sched_rm_details();
                course_sched_rm_table();
            }else if(data.result=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');                
            }else{
                toastr.error(data.result);
                thisBtn.addClass('input-error');
                course_sched_rm_table();
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
$(document).on('click', '#studentShiftModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#studentViewModal input[name="id"]').val();
    var shift_to = $('#studentShiftModal select[name="shift_to"] option:selected').val();
    var branch = $('#studentShiftModal select[name="branch"] option:selected').val();
    var curriculum = $('#studentShiftModal select[name="curriculum"] option:selected').val();
    var form_data = {
        id:id,
        shift_to:shift_to,
        branch:branch,
        curriculum:curriculum
    };
    $.ajax({
        url: base_url+'/rims/student/studentShiftModalSubmit',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            if(data.result=='success'){
                toastr.success('Success');
                thisBtn.addClass('input-success');
            }else{
                toastr.error(data.result);
                thisBtn.addClass('input-error');
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
$(document).on('click', '#studentPrintModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#studentViewModal input[name="id"]').val();
    var level = $('#studentPrintModal select[name="level"] option:selected').val();
    var purpose = $('#studentPrintModal select[name="purpose"] option:selected').val();
    var remarks = $('#studentPrintModal select[name="remarks"] option:selected').val();
    var form_data = {
        id:id,
        level:level,
        purpose:purpose,
        remarks:remarks
    };
    $.ajax({
        url: base_url+'/rims/student/studentPrintSubmit',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            if(data.result=='success'){
                toastr.success('Success');
                thisBtn.addClass('input-success');
                window.open(base_url+'/students/tor/'+data.id_no+'/'+data.level+'/'+data.dateTime, '_blank');
            }else{
                toastr.error(data.result);
                thisBtn.addClass('input-error');
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
$(document).off('click', '#useThisCurriculum').on('click', '#useThisCurriculum', function (e) {
    var thisBtn = $(this);
    var id = $('#studentViewModal input[name="id"]').val();
    var level = $('#studentCurriculumModal select[name="level"] option:selected').val();
    var program = $('#studentCurriculumModal select[name="program"] option:selected').val();
    var curriculum = $('#studentCurriculumModal select[name="curriculum"] option:selected').val();
    var branch = $('#studentCurriculumModal select[name="branch"] option:selected').val();
    var form_data = {
        id:id,
        level:level,
        program:program,
        curriculum:curriculum,
        branch:branch
    };
    $.ajax({
        url: base_url+'/rims/student/useThisCurriculum',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            if(data.result=='success'){
                toastr.success('Success');
                thisBtn.addClass('input-success');
            }else{
                toastr.error(data.result);
                thisBtn.addClass('input-error');
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
$(document).off('change', '#specialization_name_select').on('change', '#specialization_name_select', function (e) {
    var thisBtn = $(this);
    var id = $('#studentCurriculumModal input[name="id"]').val();
    var program_level = $('#studentCurriculumModal select[name="level"] option:selected').val();
    var val = thisBtn.val();
    var form_data = {
        id:id,
        val:val,
        program_level:program_level
    };
    $.ajax({
        url: base_url+'/rims/student/specializationNameSubmit',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            if(data.result=='success'){
                toastr.success('Success');
                thisBtn.addClass('input-success');
            }else{
                toastr.error(data.result);
                thisBtn.addClass('input-error');
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