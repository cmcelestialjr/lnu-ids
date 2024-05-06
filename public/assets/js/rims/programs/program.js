//programEdit
view_programs();
list_departments();
$(document).on('change', '#programsDiv .select2', function (e) {
    view_programs();
});
$(document).on('change', '#summary .select2', function (e) {
    list_departments();
});
$(document).on('input', '#programsNewModal', function (e) {
    var name = $('#programsNewModal input[name="name"]').val();
    var shorten = $('#programsNewModal input[name="shorten"]').val();
    var code = $('#programsNewModal input[name="code"]').val();
    $('#programsNewModal input[name="name"]').removeClass('border-require');
    $('#programsNewModal input[name="shorten"]').removeClass('border-require');
    $('#programsNewModal input[name="code"]').removeClass('border-require');
    if(name==''){
        $('#programsNewModal input[name="name"]').addClass('border-require');
    }
    if(shorten==''){
        $('#programsNewModal input[name="shorten"]').addClass('border-require');
    }
    if(code==''){
        $('#programsNewModal input[name="code"]').addClass('border-require');
    }
});
$(document).off('change', '#programEdit select[name="department"]').on('change', '#programEdit select[name="department"]', function (e) {
    $('#programEdit select[name="unit"]').empty();
    programFetchUnit();
});
$(document).on('click', '#programsDiv .viewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/viewModal';
    var modal = 'default';
    var modal_size = 'modal-xxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/programs/curriculumTable',
        tid:'curriculumTable',
        id:id,
        level:'All',
        status:'All'
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#programsDiv .programEdit', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/programEdit';
    var modal = 'primary';
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
$(document).on('click', '#programsDiv .programStatus,#summary .programStatus', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/programStatusModal';
    var modal = 'primary';
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
$(document).on('click', '#programsDiv .programNewModal', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/rims/programs/programNewModal';
    var modal = 'primary';
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
                list_departments();
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
$(document).on('click', '#programsNewModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var level = $('#programsNewModal select[name="level"] option:selected').val();
    var department = $('#programsNewModal select[name="department"] option:selected').val();
    var unit = $('#programsNewModal select[name="unit"] option:selected').val();
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
            unit:unit,
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
$(document).on('click', '#programEdit button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#programEdit input[name="id"]').val();
    var department = $('#programEdit select[name="department"] option:selected').val();
    var unit = $('#programEdit select[name="unit"] option:selected').val();
    var name = $('#programEdit input[name="name"]').val();
    var shorten = $('#programEdit input[name="shorten"]').val();
    var code = $('#programEdit input[name="code"]').val();
    var remarks = $('#programEdit textarea[name="remarks"]').val();
    var x = 0;
    if(name==''){
        $('#programEdit input[name="name"]').addClass('border-require');
        x++;
    }
    if(shorten==''){
        $('#programEdit input[name="shorten"]').addClass('border-require');
        x++;
    }
    if(code==''){
        $('#programEdit input[name="code"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            department:department,
            unit:unit,
            name:name,
            shorten:shorten,
            code:code,
            remarks:remarks
        };
        $.ajax({
            url: base_url+'/rims/programs/programUpdate',
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
function view_programs(){
    var thisBtn = $('#programsDiv .select2');
    var status_id = $('#programsDiv select[name="status"] option:selected').val();
    var branch_id = $('#programsDiv select[name="branch"] option:selected').val();
    var level_id = $('#programsDiv select[name="level"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/programs/viewTable',
        tid:'viewTable',
        status_id:status_id,
        branch_id:branch_id,
        level_id:level_id
    };
    loadTablewLoader(form_data,thisBtn);
}
function view_program_code(id){
    var form_data = {
        url_table:base_url+'/rims/programs/programCodesList',
        tid:'programCodesList',
        id:id
    };
    loadTable(form_data);
}
function programFetchUnit(){
    var department_id = $('#programEdit select[name="department"] option:selected').val();
    unitByDepartment(department_id);
}
function list_departments(){
    var thisBtn = $('#summary #departmentsDiv');
    var status_id = $('#summary select[name="status"] option:selected').val();
    var branch = $('#summary select[name="branch"] option:selected').val();
    var level = $('#summary select[name="level"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/programs/departments',
        tid:'departmentsShow',
        status_id:status_id,
        branch:branch,
        level:level
    };
    loadDivwDisabled(form_data,thisBtn);
}
