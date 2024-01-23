// $(document).on('click', '#courseSchedRmModal #courseSchedRmTable .scheduleTimeUpdate', function (e) {
//     var thisB = $(this);
//     var thisBtn = $('#courseSchedRmModal #courseSchedRmTable .scheduleTimeUpdate');
//     var id = $('#courseSchedRmModal input[name="id"]').val();
//     var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();
//     var hour_no = $('#courseSchedRmModal #schedule select[name="hour_no"]').val();
//     var type = $('#courseSchedRmModal #rm_instructor input[name="type"]:checked').val();
//     var time_from = $('#courseSchedRmModal #schedule input[name="time_from"]').val();
//     var time_to = $('#courseSchedRmModal #schedule input[name="time_to"]').val();
//     var room_id = $('#courseSchedRmModal #rm_instructor select[name="room"] option:selected').val();
//     var hours = $('#courseSchedRmModal #rm_instructor input[name="hours"]').val();
//     var minutes = $('#courseSchedRmModal #rm_instructor select[name="minutes"] option:selected').val();
//     var t = $(this).data('t');
//     var d = $(this).data('d');
//     var x = 0;
//     var time_from_valid = moment(time_from, "hh:mma", true).isValid();
//     var time_to_valid = moment(time_to, "hh:mma", true).isValid();
//     $('#courseSchedRmModal #schedule input[name="time_from"]').removeClass('border-require');
//     $('#courseSchedRmModal #schedule input[name="time_to"]').removeClass('border-require');
//     if(time_from_valid==false){
//         $('#courseSchedRmModal #schedule input[name="time_from"]').addClass('border-require');
//         toastr.error('Invalid time format in Time From');
//         x++;
//     }
//     if(time_to_valid==false){
//         $('#courseSchedRmModal #schedule input[name="time_to"]').addClass('border-require');
//         toastr.error('Invalid time format in Time To');
//         x++;
//     }
//     if(hours<0 || hours>=8){
//         $('#courseSchedRmModal #rm_instructor input[name="hours"]').addClass('border-require');
//         toastr.error('Hours must be lower than 8');
//         x++;
//     }
//     if(x==0){
//         var form_data = {
//             id:id,
//             t:t,
//             d:d,
//             schedule_id:schedule_id,
//             hour_no:hour_no,
//             type:type,
//             time_from:time_from,
//             time_to:time_to,
//             room_id:room_id,
//             hours:hours,
//             minutes:minutes
//         };
//         $.ajax({
//             url: base_url+'/rims/sections/scheduleTimeUpdate',
//             type: 'POST',
//             headers: {
//                 'X-CSRF-TOKEN': CSRF_TOKEN
//             },
//             data:form_data,
//             cache: false,
//             dataType: 'json',
//             beforeSend: function() {
//                 thisBtn.attr('disabled','disabled');
//                 thisB.removeClass('btn-no-design');
//                 thisB.removeClass('bg-info');
//                 thisB.removeClass('bg-lightgreen');            
//                 thisB.removeClass('bg-primary');
//                 thisB.removeClass('bg-secondary');
//                 thisB.removeClass('bg-warning');
//                 thisB.removeClass('bg-danger');
//                 thisB.addClass('bg-success');
//                 thisB.addClass('input-loading');
//             },
//             success : function(data){
//                 thisBtn.removeAttr('disabled');
//                 thisB.removeClass('input-loading');
//                 if(data.result=='success'){
//                     toastr.success('Success');
//                     thisB.addClass('input-success');
//                     $('#courseSchedRmModal #courseSchedRmTable .blank').html('&nbsp;&nbsp;');
//                     $('#courseSchedRmModal #schedule select[name="schedule"] option[value="' + data.sched_id + '"]').remove();
//                     $('#courseSchedRmModal #schedule select[name="schedule"]').append($('<option value="'+data.sched_id+'" selected>'+data.sched_name+'</option>'));
//                     $('#courseSchedRmModal #courseSchedRmTable #scheduleRemoveDayTr').removeClass('hide');  
//                     $('#courseSchedRmModal #courseSchedRmTable .bg-success').html('&nbsp;&nbsp;');
//                     $('#courseSchedRmModal #courseSchedRmTable .bg-success').addClass('blank');
//                     $('#courseSchedRmModal #courseSchedRmTable .bg-success').removeClass('bg-success');
//                     $.each(data.list_x, function(index, val) {
//                         var split = val.split("_");
//                         $('#courseSchedRmModal #courseSchedRmTable #dayTime'+split[0]).removeClass('btn-no-design');
//                         $('#courseSchedRmModal #courseSchedRmTable #dayTime'+split[0]).addClass('bg-success btn-no-design');
//                         $('#courseSchedRmModal #courseSchedRmTable #dayTime'+split[0]).html(split[1]);
//                     });
//                     course_sched_rm_details();
//                     course_sched_rm_rm_instructor();
//                 }else if(data.result=='conflict'){
//                     toastr.error('Conflict schedule please check carefully.');
//                     thisB.removeClass('bg-success');
//                     thisB.addClass('input-error'); 
//                 }else if(data.result=='time'){
//                     toastr.error('Time From must less than Time To.');
//                     $('#courseSchedRmModal #schedule input[name="time_from"]').addClass('border-require');
//                     $('#courseSchedRmModal #schedule input[name="time_to"]').addClass('border-require');
//                     thisB.removeClass('bg-success');
//                     thisB.addClass('input-error'); 
//                 }else if(data.result=='school_from'){
//                     toastr.error('Time From must be greater than or equal to default time '+data.time_from);
//                     $('#courseSchedRmModal #schedule input[name="time_from"]').addClass('border-require');
//                     thisB.removeClass('bg-success');
//                     thisB.addClass('input-error'); 
//                 }else if(data.result=='school_to'){
//                     toastr.error('Time To must be less than or equal to default time '+data.time_to);
//                     $('#courseSchedRmModal #schedule input[name="time_to"]').addClass('border-require');
//                     thisB.removeClass('bg-success');
//                     thisB.addClass('input-error');
//                 }else{
//                     toastr.error('Error.');
//                     thisB.addClass('input-error'); 
//                 }
//                 setTimeout(function() {
//                     thisB.removeClass('input-success');
//                     thisB.removeClass('input-error');
//                 }, 3000);
//             },
//             error: function (){
//                 toastr.error('Error!');
//                 thisBtn.removeAttr('disabled');
//                 thisB.removeClass('input-success');
//                 thisB.removeClass('input-error');
//             }
//         });
//     }
// });
$(document).on('click', '#courseSchedRmModal #rm_instructor input[name="type"]', function (e) {
    var thisBtn = $(this);
    var type = thisBtn.val();
    var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();
    if(schedule_id!='new'){
        var form_data = {
            schedule_id:schedule_id,
            type:type
        };
        $.ajax({
            url: base_url+'/rims/sections/typeUpdate',
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
                    view_schedule();
                }else{
                    toastr.error('Error.');
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
    }    
});
$(document).on('click', '#minMaxModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#minMaxModal input[name="id"]').val();
    var min_student = $('#minMaxModal input[name="min_student"]').val();
    var max_student = $('#minMaxModal input[name="max_student"]').val();
    var form_data = {
        id:id,
        min_student:min_student,
        max_student:max_student
    };
    $.ajax({
        url: base_url+'/rims/sections/minMaxSubmit',
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
                $("#modal-info").modal('hide');
                course_view_table(id,thisBtn);
            }else{
                toastr.error('Error.');
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
$(document).on('change', '#courseSchedRmModal #rm_instructor .select2-rm_instructor', function (e) {
    rm_instructor_update();
});
$(document).on('blur', '#courseSchedRmModal #rm_instructor .input-rm_instructor', function (e) {
    rm_instructor_update();
});
$(document).on('blur', '.min_max_section', function (e) {
    var thisBtn = $(this);
    var val = thisBtn.val();
    var id = thisBtn.data('id');
    var t = thisBtn.data('t');
    if(val<=0){
        thisBtn.addClass('border-require');
        thisBtn.addClass('input-error');
    }else{
        var form_data = {
            id:id,
            type:t,
            val:val
        };
        $.ajax({
            url: base_url+'/rims/sections/minMaxStudent',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                thisBtn.attr('disabled','disabled'); 
                thisBtn.removeClass('input-error');
                thisBtn.addClass('input-loading');
                thisBtn.removeClass('border-require');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading'); 
                if(data.result=='success'){
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
                }else{
                    toastr.error('Error.');
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
    }    
});
