$(document).on('click', '#courseAnotherModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var course_id = $('#courseAnotherModal input[name="course_id"]').val();
    var course_id_another = $('.anotherCourseSelected:checked').val();

    if(course_id_another){
        var form_data = {
            course_id:course_id,
            course_id_another:course_id_another
        };
        $.ajax({
            url: base_url+'/rims/enrollment/courseAnotherSubmit',
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
                    $('#modal-primary').modal('hide');
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_conflict'+course_id).removeClass('btn-danger btn-danger-scan');
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_conflict'+course_id).addClass('btn-primary btn-primary-scan');
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_checked'+course_id).removeClass('hide');
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_checked'+course_id).attr('checked', 'checked');
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_checked'+course_id).data('cid', course_id_another);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_code'+course_id).html('<br>'+data.code);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_name'+course_id).html('<br>'+data.name);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_units'+course_id).html('<br>'+data.units);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_pre_name'+course_id).html('<br>'+data.pre_name);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_schedule'+course_id).html('<br>'+data.schedule);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_room'+course_id).html('<br>'+data.room);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_instructor'+course_id).html('<br>'+data.instructor);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_status'+course_id).html('<br>'+data.status);
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
    }else{
        toastr.error('Please select a course');
    }
});