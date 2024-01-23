$(document).on('click', '#curriculumNewModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#curriculumNewModal input[name="id"]').val();
    var year_from = $('#curriculumNewModal input[name="year_from"]').val();
    var year_to = $('#curriculumNewModal input[name="year_to"]').val();
    var name = $('#curriculumNewModal input[name="name"]').val();
    var remarks = $('#curriculumNewModal input[name="remarks"]').val();
    if(name==''){
        toastr.error('Please input name');
        $('#curriculumNewModal input[name="name"]').addClass('border-require');
    }else{
        var form_data = {
            id:id,
            year_from:year_from,
            year_to:year_to,
            name:name,
            remarks:remarks
        };
        $.ajax({
            url: base_url+'/rims/programs/curriculumNewSubmit',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            beforeSend: function() {
                thisBtn.attr('disabled','disabled'); 
                thisBtn.addClass('input-loading');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading'); 
                if(data=='error'){
                    toastr.error('Error.');
                    thisBtn.addClass('input-error');                
                }else{
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
                    $('#curriculumModal #curriculumDiv #curriculums').html(data);
                    $(".select2-default").select2({
                        dropdownParent: $("#curriculums")
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
    }
});
$(document).on('click', '#newCourseModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var x = 0;
    var id = $('#curriculumModal #curriculumDiv select[name="curriculum"] option:selected').val();
    var grade_period = $('#newCourseModal select[name="grade_period"] option:selected').val();
    var year_level = $('#newCourseModal select[name="year_level"] option:selected').val();
    var lab_group = $('#newCourseModal select[name="lab_group"] option:selected').val();
    var course_type = $('#newCourseModal select[name="course_type"] option:selected').val();
    var code = $('#newCourseModal input[name="code"]').val();
    var name = $('#newCourseModal input[name="name"]').val();
    var units = $('#newCourseModal input[name="units"]').val();
    var pre_name = $('#newCourseModal input[name="pre_name"]').val();
    var lab = $('#newCourseModal input[name="lab"]').val();
    var pay_units = $('#newCourseModal input[name="pay_units"]').val();
    var courses = [];
    $('#newCourseModal .courses:checked').each(function() {
        courses.push($(this).val());
    });
    if(code==''){
        $('#newCourseModal input[name="code"]').addClass('border-require');
        x++;
    }
    if(name==''){
        $('#newCourseModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(units=='' || units<= 0){
        $('#newCourseModal input[name="units"]').addClass('border-require');
        x++;
    }
    if(pre_name==''){
        $('#newCourseModal input[name="pre_name"]').addClass('border-require');
        x++;
    }
    if(lab==''){
        $('#newCourseModal input[name="lab"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            grade_period:grade_period,
            year_level:year_level,
            code:code,
            name:name,
            units:units,
            pre_name:pre_name,
            courses:courses,
            lab:lab,
            pay_units:pay_units,
            lab_group:lab_group,
            course_type:course_type
        };
        $.ajax({
            url: base_url+'/rims/programs/newCourseSubmit',
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
                    $('#newCourseModal input[name="code"]').val('');
                    $('#newCourseModal input[name="name"]').val('');
                    $('#newCourseModal input[name="units"]').val('');
                    $('#newCourseModal input[name="pre_name"]').val('None');
                    $('#newCourseModal .courses').prop('checked', false);
                    $('#newCourseModal .all').prop('checked', false);
                    curriculum_div(thisBtn);
                }else if(data.result=='exists'){
                    toastr.error('Course Code or Descriptive Title already exists!');
                    thisBtn.addClass('input-error');
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
