$(document).on('click', '#programsAddModal .program', function (e) {
    var thisBtn = $(this);
    var id = $('#programsAddModal input[name="id"]').val();
    var program_id = thisBtn.data('id');
    var form_data = {
        id:id,
        program_id:program_id
    };
    $.ajax({
        url: base_url+'/rims/departments/programsAddSubmit',
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
                $('#programsAddModal #programDeptName'+program_id).html(data.dept);
                view_program_list(id);
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