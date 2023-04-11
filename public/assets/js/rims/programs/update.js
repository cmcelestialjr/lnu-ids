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
$(document).on('click', '#courseUpdateModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var x = 0;
    var id = $('#courseUpdateModal input[name="id"]').val();
    var code = $('#courseUpdateModal input[name="code"]').val();
    var name = $('#courseUpdateModal input[name="name"]').val();
    var units = $('#courseUpdateModal input[name="units"]').val();
    var pre_name = $('#courseUpdateModal input[name="pre_name"]').val();
    var lab = $('#courseUpdateModal input[name="lab"]').val();
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
            lab:lab
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
$(document).on('click', '#programCodesModal .programCodeStatus', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/programs/programCodeStatus',
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
$(document).on('click', '#programCodeEditModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#programCodeEditModal input[name="id"]').val();
    var name = $('#programCodeEditModal input[name="name"]').val();
    var remarks = $('#programCodeEditModal textarea[name="remarks"]').val();
    var x = 0;
    if(name==''){
        $('#programCodeEditModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            name:name,
            remarks:remarks
        };
        $.ajax({
            url: base_url+'/rims/programs/programCodeEditSubmit',
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
                    view_program_code(data.id);
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
$(document).on('click', '#programStatusModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#programStatusModal input[name="id"]').val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/programs/programStatusSubmit',
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
                $('#programsDiv #programStatus'+id).removeClass('btn-success btn-success-scan');
                $('#programsDiv #programStatus'+id).removeClass('btn-danger btn-danger-scan');
                $('#programsDiv #programStatus'+id).addClass(data.btn_class);
                $('#programsDiv #programStatus'+id).html(data.btn_html);
                
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