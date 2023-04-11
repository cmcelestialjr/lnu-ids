$(document).on('click', '#curriculumNewModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#curriculumNewModal input[name="id"]').val();
    var year_from = $('#curriculumNewModal input[name="year_from"]').val();
    var year_to = $('#curriculumNewModal input[name="year_to"]').val();
    var remarks = $('#curriculumNewModal input[name="remarks"]').val();
    var form_data = {
        id:id,
        year_from:year_from,
        year_to:year_to,
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
});
$(document).on('click', '#newCourseModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var x = 0;
    var id = $('#curriculumModal #curriculumDiv select[name="curriculum"] option:selected').val();
    var grade_period = $('#newCourseModal select[name="grade_period"] option:selected').val();
    var year_level = $('#newCourseModal select[name="year_level"] option:selected').val();
    var code = $('#newCourseModal input[name="code"]').val();
    var name = $('#newCourseModal input[name="name"]').val();
    var units = $('#newCourseModal input[name="units"]').val();
    var pre_name = $('#newCourseModal input[name="pre_name"]').val();
    var lab = $('#newCourseModal input[name="lab"]').val();
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
            lab:lab
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
$(document).on('click', '#programCodeNewModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#programCodeNewModal input[name="id"]').val();
    var name = $('#programCodeNewModal input[name="name"]').val();
    var remarks = $('#programCodeNewModal textarea[name="remarks"]').val();
    var x = 0;
    if(name==''){
        $('#programCodeNewModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            name:name,
            remarks:remarks
        };
        $.ajax({
            url: base_url+'/rims/programs/programCodeNewSubmit',
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
                    view_program_code(id);
                }else if(data.result=='exists'){
                    toastr.error('Code already exists!');
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
$(document).on('click', '#programsNewModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var level = $('#programsNewModal select[name="level"] option:selected').val();
    var department = $('#programsNewModal select[name="department"] option:selected').val();
    var name = $('#programsNewModal input[name="name"]').val();
    var shorten = $('#programsNewModal input[name="shorten"]').val();
    var code = $('#programsNewModal input[name="code"]').val();
    var x = 0;
    if(name==''){
        $('#programsNewModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(shorten==''){
        $('#programsNewModal input[name="shorten"]').addClass('border-require');
        x++;
    }
    if(code==''){
        $('#programsNewModal input[name="code"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            level:level,
            department:department,
            name:name,
            shorten:shorten,
            code:code
        };
        $.ajax({
            url: base_url+'/rims/programs/programsNewSubmit',
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
                    view_programs();               
                }else if(data.result=='exists'){
                    toastr.error('Name/Shorten/Code already exists!');
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
$(document).on('click', '#newModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var name = $('#newModal input[name="name"]').val();
    var shorten = $('#newModal input[name="shorten"]').val();
    var code = $('#newModal input[name="code"]').val();
    var x = 0;
    if(name==''){
        $('#newModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(shorten==''){
        $('#newModal input[name="shorten"]').addClass('border-require');
        x++;
    }
    if(code==''){
        $('#newModal input[name="code"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            shorten:shorten,
            code:code
        };
        $.ajax({
            url: base_url+'/rims/departments/newModalSubmit',
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
                    view_departments();
                }else if(data.result=='exists'){
                    toastr.error('Name/Shorten/Code already exists!');
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