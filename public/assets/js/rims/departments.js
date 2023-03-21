view_departments();
$(document).on('click', '#departmentsDiv .programsModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/departments/programsModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/departments/programsList',
        tid:'programsList',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#programsModal .programAddModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/departments/programAddModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/departments/programAddList',
        tid:'programAddList',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#departmentsDiv .newModal', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/rims/departments/newModal';
    var modal = 'default';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#departmentsDiv .editModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/departments/editModal';
    var modal = 'default';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('input', '#newModal', function (e) {
    var name = $('#newModal input[name="name"]').val();
    var shorten = $('#newModal input[name="shorten"]').val();
    var code = $('#newModal input[name="code"]').val();
    $('#newModal input[name="name"]').removeClass('border-require');
    $('#newModal input[name="shorten"]').removeClass('border-require');
    $('#newModal input[name="code"]').removeClass('border-require');
    if(name==''){
        $('#newModal input[name="name"]').addClass('border-require');
    }
    if(shorten==''){
        $('#newModal input[name="shorten"]').addClass('border-require');
    }
    if(code==''){
        $('#newModal input[name="code"]').addClass('border-require');
    }
});
$(document).on('input', '#editModal', function (e) {
    var name = $('#newModal input[name="name"]').val();
    var shorten = $('#newModal input[name="shorten"]').val();
    var code = $('#newModal input[name="code"]').val();
    $('#newModal input[name="name"]').removeClass('border-require');
    $('#newModal input[name="shorten"]').removeClass('border-require');
    $('#newModal input[name="code"]').removeClass('border-require');
    if(name==''){
        $('#newModal input[name="name"]').addClass('border-require');
    }
    if(shorten==''){
        $('#newModal input[name="shorten"]').addClass('border-require');
    }
    if(code==''){
        $('#newModal input[name="code"]').addClass('border-require');
    }
});
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
$(document).on('click', '#editModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#editModal input[name="id"]').val();
    var name = $('#editModal input[name="name"]').val();
    var shorten = $('#editModal input[name="shorten"]').val();
    var code = $('#editModal input[name="code"]').val();
    var x = 0;
    if(name==''){
        $('#editModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(shorten==''){
        $('#editModal input[name="shorten"]').addClass('border-require');
        x++;
    }
    if(code==''){
        $('#editModal input[name="code"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            name:name,
            shorten:shorten,
            code:code
        };
        $.ajax({
            url: base_url+'/rims/departments/editModalSubmit',
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
                    view_departments();
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
function view_program_list(id){
    var form_data = {
        url_table:base_url+'/rims/departments/programsList',
        tid:'programsList',
        id:id
    };
    loadTable(form_data);
}
function view_departments(){
    var form_data = {
        url_table:base_url+'/rims/departments/viewTable',
        tid:'viewTable'
    };
    loadTable(form_data);
}