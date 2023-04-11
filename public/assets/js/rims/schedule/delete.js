$(document).on('click', '#courseSchedRmModal #courseSchedRmTable .scheduleRemoveDay', function (e) {
    var thisB = $(this);
    var thisBtn = $('#courseSchedRmModal #courseSchedRmTable .scheduleRemoveDay');
    var id = $('#courseSchedRmModal input[name="id"]').val();
    var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();
    var d = $(this).data('d');
    var form_data = {
        id:id,
        d:d,
        schedule_id:schedule_id
    };
    $.ajax({
        url: base_url+'/rims/sections/scheduleRemoveDay',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisB.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisB.removeClass('input-loading');
            if(data.result=='success'){
                toastr.success('Success');
                thisB.addClass('input-success');
                course_sched_rm_schedule();
                $('#courseSchedRmModal #courseSchedRmTable .bg-success').html('&nbsp;&nbsp;');
                $('#courseSchedRmModal #courseSchedRmTable .bg-success').addClass('blank');
                $('#courseSchedRmModal #courseSchedRmTable .bg-success').removeClass('bg-success');
                var list_x = data.list_x;
                if(list_x.length === 0){
                    $('#courseSchedRmModal #courseSchedRmTable #scheduleRemoveDayTr').addClass('hide');                      
                }else{
                    $('#courseSchedRmModal #courseSchedRmTable #scheduleRemoveDayTr').removeClass('hide');
                    $.each(list_x, function(index, val) {
                        var split = val.split("_");
                        $('#courseSchedRmModal #courseSchedRmTable #dayTime'+split[0]).removeClass('bg-success-light btn-no-design');
                        $('#courseSchedRmModal #courseSchedRmTable #dayTime'+split[0]).addClass('bg-success btn-no-design');
                        $('#courseSchedRmModal #courseSchedRmTable #dayTime'+split[0]).html(split[1]);
                    });
                }
                setTimeout(function() {
                    course_sched_rm_details();
                    course_sched_rm_rm_instructor();
                }, 1000);
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
});
$(document).on('click', '#courseSchedRmModal #rm_instructor button[name="delete"]', function (e) {
    var thisBtn = $(this);
    var id = $('#courseSchedRmModal input[name="id"]').val();
    var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();
    var form_data = {
        id:id,
        schedule_id:schedule_id
    };
    $.ajax({
        url: base_url+'/rims/sections/scheduleRemove',
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
                course_sched_rm_schedule();
                $('#courseSchedRmModal #courseSchedRmTable .bg-success').html('&nbsp;&nbsp;');
                $('#courseSchedRmModal #courseSchedRmTable .bg-success').removeClass('bg-success');
                var list_x = data.list_x;
                if(list_x.length === 0){
                    $('#courseSchedRmModal #courseSchedRmTable #scheduleRemoveDayTr').addClass('hide');                      
                }else{
                    $('#courseSchedRmModal #courseSchedRmTable #scheduleRemoveDayTr').removeClass('hide');
                    $.each(list_x, function(index, val) {                    
                        var split = val.split("_");
                        $('#courseSchedRmModal #courseSchedRmTable #dayTime'+split[0]).removeClass('btn-no-design');
                        $('#courseSchedRmModal #courseSchedRmTable #dayTime'+split[0]).addClass('bg-success btn-no-design');
                        $('#courseSchedRmModal #courseSchedRmTable #dayTime'+split[0]).html(split[1]);
                    });
                }
                setTimeout(function() {
                    course_sched_rm_details();
                    course_sched_rm_rm_instructor();
                }, 1000);
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