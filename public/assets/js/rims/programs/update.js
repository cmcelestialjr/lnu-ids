$(document).on('click', '#curriculumModal .curriculumStatus', function (e) {
    var thisBtn = $(this);
    var id = $('#curriculumModal select[name="curriculum"] option:selected').val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/programs/curriculumStatus',
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
                thisBtn.removeClass('btn-danger btn-danger-scan');
                thisBtn.removeClass('btn-success btn-success-scan');
                thisBtn.addClass(data.btn_class);
                thisBtn.html(data.btn_html);
                curriculum_div(thisBtn);
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
$(document).on('blur', '#curriculumModal .curriculum_input', function (e) {
    var thisBtn = $(this);
    var id = $('#curriculumModal select[name="curriculum"] option:selected').val();
    var n = thisBtn.data('n');
    var val = thisBtn.val();
    var form_data = {
        id:id,
        n:n,
        val:val
    };
    $.ajax({
        url: base_url+'/rims/programs/curriculumInputUpdate',
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
$(document).on('click', '#courseUpdateModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var x = 0;
    var id = $('#courseUpdateModal input[name="id"]').val();
    var code = $('#courseUpdateModal input[name="code"]').val();
    var name = $('#courseUpdateModal input[name="name"]').val();
    var units = $('#courseUpdateModal input[name="units"]').val();
    var pre_name = $('#courseUpdateModal input[name="pre_name"]').val();
    var lab = $('#courseUpdateModal input[name="lab"]').val();
    var pay_units = $('#courseUpdateModal input[name="pay_units"]').val();
    var specialization_name = $('#courseUpdateModal input[name="specialization_name"]').val();
    var lab_group = $('#courseUpdateModal select[name="lab_group"] option:selected').val();
    var course_type = $('#courseUpdateModal select[name="course_type"] option:selected').val();
    var courses = [];
    $('#courseUpdateModal .courses:checked').each(function() {
        courses.push($(this).val());
    });
    if(code==''){
        $('#courseUpdateModal input[name="code"]').addClass('border-require');
        x++;
    }
    if(name==''){
        $('#courseUpdateModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(units=='' || units<=0){
        $('#courseUpdateModal input[name="units"]').addClass('border-require');
        x++;
    }
    if(pre_name==''){
        $('#courseUpdateModal input[name="pre_name"]').addClass('border-require');
        x++;
    }
    if(lab==''){
        $('#courseUpdateModal input[name="lab"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            code:code,
            name:name,
            units:units,
            pre_name:pre_name,
            courses:courses,
            lab:lab,
            pay_units:pay_units,
            lab_group:lab_group,
            course_type:course_type,
            specialization_name:specialization_name
        };
        $.ajax({
            url: base_url+'/rims/programs/courseUpdateSubmit',
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
$(document).on('click', '#curriculumDiv #curriculumTable .courseStatus', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/programs/courseStatus',
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
            if(data.result=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');
            }else{
                toastr.success('Success');
                thisBtn.addClass('input-success');
                thisBtn.removeClass('btn-danger btn-danger-scan');
                thisBtn.removeClass('btn-success btn-success-scan');
                thisBtn.addClass(data.btn_class);
                thisBtn.html(data.btn_html);
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

$(document).on('click', '#newCourseModal #courseSelectSubmit', function (e) {
    var thisBtn = $(this);
    var id = $('#newCourseModal .courseSelect option:selected').val();
    if(!id){
        toastr.success('Please Select a course.');
        $('#newCourseModal #courseSelect').addClass('border-require');
    }else{
        var form_data = {
            id:id
        };
        $.ajax({
            url: base_url+'/rims/programs/courseInfo',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            beforeSend: function() {
                thisBtn.attr('disabled','disabled');
                thisBtn.addClass('input-loading');
                $('#newCourseModal #courseInfo').addClass('disabled');
                $('#newCourseModal #courseSelect').removeClass('border-require');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading');
                if(data=='error'){
                    toastr.error('Error.');
                    thisBtn.addClass('input-error');
                }else{
                    $('#newCourseModal #courseInfo').removeClass('disabled');
                    $('#newCourseModal #courseInfo').html(data);
                    $(".select2-div").select2({
                        dropdownParent: $("#courseInfo")
                    });
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
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
