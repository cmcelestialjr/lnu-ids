view_curriculums();
list_departments();
$(document).off('click', '#curriculumDiv button[name="list"]').on('click', '#curriculumDiv button[name="list"]', function (e) {
    view_curriculums();
});
$(document).off('click', '#summary button[name="list"]').on('click', '#summary button[name="list"]', function (e) {
    list_departments();
});
$(document).off('click', '#curriculumDiv .viewModal').on('click', '#curriculumDiv .viewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/curriculums/viewModal/'+id;
    var modal = 'default';
    var modal_size = 'modal-xxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModal(form_data,thisBtn);
});
$(document).off('click', '#curriculumDiv .editModal, #summary .editModal')
    .on('click', '#curriculumDiv .editModal, #summary .editModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var x = thisBtn.data('x');
    var url = base_url+'/rims/curriculums/editModal/'+id;
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        x:x
    };
    loadModal(form_data,thisBtn);
});
$(document).off('click', '#curriculumDiv .newModal').on('click', '#curriculumDiv .newModal', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/rims/curriculums/newModal';
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModal(form_data,thisBtn);
});
$(document).off('change', '#newModal select[name="level"]').on('change', '#newModal select[name="level"]', function (e) {
    var thisBtn = $('#newModal select[name="program"]');
    var id = $(this).val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/curriculums/programList/'+id,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            if(data.result=='success'){
                thisBtn.empty();
                $.each(data.programs, function(index, program) {
                    thisBtn.append('<option value="' + program.id + '">' + program.name + '</option>');
                });
            }else{
                toastr.error('Error.');
            }
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
        }
    });
});
$(document).off('click', '#editModal button[name="submit"]').on('click', '#editModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var x = 0;
    var id = $('#editModal input[name="id"]').val();
    var dx = $('#editModal input[name="id"]').data('x');
    var name = $('#editModal input[name="name"]').val();
    var year_from = $('#editModal input[name="year_from"]').val();
    var year_to = $('#editModal input[name="year_to"]').val();
    var status = $('#editModal select[name="status"] option:selected').val();
    var remarks = $('#editModal textarea[name="remarks"]').val();
    var branch = $('#curriculumDiv select[name="branch"] option:selected').val();

    $('#newModal input[name="year_from"]').removeClass('border-require');
    if (!isYear(year_from)) {
        $('#editModal input[name="year_from"]').addClass('border-require');
        toastr.error('Please input valid year!');
        x++;
    }

    if(x==0){
        var form_data = {
            id:id,
            name:name,
            year_from:year_from,
            year_to:year_to,
            status:status,
            remarks:remarks,
            branch:branch
        };
        $.ajax({
            url: base_url+'/rims/curriculums/updateSubmit/'+id,
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
                    $('#curriculumDiv #yearFromSpan'+dx).html(data.year_from);
                    $('#curriculumDiv #statusBtn'+dx).removeClass('btn-success');
                    $('#curriculumDiv #statusBtn'+dx).removeClass('btn-danger');
                    $('#curriculumDiv #statusBtn'+dx).html(data.status_name);
                    if(data.status==1){
                        $('#curriculumDiv #statusBtn'+dx).addClass('btn-success');
                    }else{
                        $('#curriculumDiv #statusBtn'+dx).addClass('btn-danger');
                    }
                    list_departments();
                }else if(data.result=='exists'){
                    toastr.error('Curriculum year from already exists!');
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
$(document).off('click', '#newModal button[name="submit"]').on('click', '#newModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var x = 0;
    var program = $('#newModal select[name="program"] option:selected').val();
    var name = $('#newModal input[name="name"]').val();
    var year_from = $('#newModal input[name="year_from"]').val();
    var year_to = $('#newModal input[name="year_to"]').val();
    var status = $('#newModal select[name="status"] option:selected').val();
    var remarks = $('#newModal textarea[name="remarks"]').val();
    var branch = $('#curriculumDiv select[name="branch"] option:selected').val();

    $('#newModal input[name="year_from"]').removeClass('border-require');
    if (!isYear(year_from)) {
        $('#newModal input[name="year_from"]').addClass('border-require');
        toastr.error('Please input valid year!');
        x++;
    }

    if(x==0){
        var form_data = {
            program:program,
            name:name,
            year_from:year_from,
            year_to:year_to,
            status:status,
            remarks:remarks,
            branch:branch
        };
        $.ajax({
            url: base_url+'/rims/curriculums/storeSubmit',
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
                    view_curriculums();
                }else if(data.result=='exists'){
                    toastr.error('Curriculum year from already exists!');
                    thisBtn.addClass('input-error');
                }else{
                    toastr.error(data.result);
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

function view_curriculums(){
    var thisBtn = $('#curriculumDiv .thisBtn');
    var status_id = $('#curriculumDiv select[name="status"] option:selected').val();
    var branch = $('#curriculumDiv select[name="branch"] option:selected').val();
    var level = $('#curriculumDiv select[name="level"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/curriculums/viewTable',
        tid:'viewTable',
        status_id:status_id,
        branch:branch,
        level:level
    };
    loadTablewLoader(form_data,thisBtn);
}
function list_departments(){
    var thisBtn = $('#summary #departmentsDiv');
    var status_id = $('#summary select[name="status"] option:selected').val();
    var branch = $('#summary select[name="branch"] option:selected').val();
    var level = $('#summary select[name="level"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/curriculums/departments',
        tid:'departmentsShow',
        status_id:status_id,
        branch:branch,
        level:level
    };
    loadDivwDisabled(form_data,thisBtn);
}
