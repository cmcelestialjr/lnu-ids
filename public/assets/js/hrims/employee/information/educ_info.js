educ_table();
$(document).ready(function() {
    $(document).off('click', '#employeeInformationModal #new-educ')
    .on('click', '#employeeInformationModal #new-educ', function (e) {
        educ_new($(this));
    });
    $(document).off('click', '#educ-new-modal #submit')
    .on('click', '#educ-new-modal #submit', function (e) {
        educ_submit(0,$(this),'new','educNewSubmit');
    });
    $(document).off('click', '#employeeInformationModal .edit-educ')
    .on('click', '#employeeInformationModal .edit-educ', function (e) {
        educ_edit($(this));
    });
    $(document).off('click', '#educ-edit-modal #submit')
    .on('click', '#educ-edit-modal #submit', function (e) {
        educ_submit($(this).data('id'),$(this),'edit','educEditSubmit');
    });
    $(document).off('click', '#employeeInformationModal .delete-educ')
    .on('click', '#employeeInformationModal .delete-educ', function (e) {
        educ_delete($(this));
    });
    $(document).off('click', '#educ-delete-modal #submit')
    .on('click', '#educ-delete-modal #submit', function (e) {
        educ_delete_submit($(this));
    });
    $(document).off('change', '#educ-edit-modal #school ,#educ-edit-modal #level')
    .on('change', '#educ-edit-modal #school ,#educ-edit-modal #level', function (e) {
        var level = $('#educ-edit-modal #level option:selected').val();
        var school = $('#educ-edit-modal #school option:selected').val();
        programSearch2(level,school);
    });
    $(document).off('change', '#educ-new-modal #school ,#educ-new-modal #level')
    .on('change', '#educ-new-modal #school ,#educ-new-modal #level', function (e) {
        var level = $('#educ-new-modal #level option:selected').val();
        var school = $('#educ-new-modal #school option:selected').val();
        programSearch2(level,school);
    });
    $(document).off('click', '#educ-new-modal #school_check')
        .on('click', '#educ-new-modal #school_check', function (e) {
        schoolNotList($(this),'new');
    });
    $(document).off('click', '#educ-edit-modal #school_check')
        .on('click', '#educ-edit-modal #school_check', function (e) {
        schoolNotList($(this),'edit');
    });
    $(document).off('click', '#educ-new-modal #program_check')
        .on('click', '#educ-new-modal #program_check', function (e) {
        programNotList($(this),'new');
    });
    $(document).off('click', '#educ-edit-modal #program_check')
        .on('click', '#educ-edit-modal #program_check', function (e) {
        programNotList($(this),'edit');
    });
    $(document).off('click', '#educ-new-modal #present_check')
    .on('click', '#educ-new-modal #present_check', function (e) {
        periodToPresent($(this),'new');
    });
    $(document).off('click', '#educ-edit-modal #present_check')
    .on('click', '#educ-edit-modal #present_check', function (e) {
        periodToPresent($(this),'edit');
    });
    $(document).off('change', '#educ-new-modal #level')
    .on('change', '#educ-new-modal #level', function (e) {
        programView('new');
    });
    $(document).off('change', '#educ-edit-modal #level')
    .on('change', '#educ-edit-modal #level', function (e) {
        programView('edit');
    });
});
function educ_table(){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/educTable',
        tid:'educTable',
        id:id
    };
    loadTable(form_data);
}
function educ_new(thisBtn){
    var url = base_url+'/hrims/employee/educNew';
    var modal = 'info';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModal(form_data,thisBtn);
}
function educ_edit(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/educEdit';
    var modal = 'info';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        fid:fid
    };
    loadModal(form_data,thisBtn);
}
function educ_delete(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/educDelete';
    var modal = 'info';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        fid:fid
    };
    loadModal(form_data,thisBtn);
}
function periodToPresent(thisBtn,type){
    $('#educ-'+type+'-modal #period_to').prop('readonly', false);
    if(thisBtn.is(':checked')){
        $('#educ-'+type+'-modal #period_to').prop('readonly', true);
    }
}
function programNotList(thisBtn,type){
    $('#educ-'+type+'-modal #program_name').addClass('hide');
    $('#educ-'+type+'-modal .program_div').removeClass('hide');
    if(thisBtn.is(':checked')){
        $('#educ-'+type+'-modal #program_name').removeClass('hide');
        $('#educ-'+type+'-modal .program_div').addClass('hide');
    }
}
function schoolNotList(thisBtn,type){
    $('#educ-'+type+'-modal #schoolSearch').removeClass('hide');
    $('#educ-'+type+'-modal .school_new').addClass('hide');
    if(thisBtn.is(':checked')){
        $('#educ-'+type+'-modal #schoolSearch').addClass('hide');
        $('#educ-'+type+'-modal .school_new').removeClass('hide');
    }
}
function programView(type){
    $('#educ-'+type+'-modal #program_div').addClass('hide');
    if($('#educ-'+type+'-modal #level option:selected').data('w')=='w'){
        $('#educ-'+type+'-modal #program_div').removeClass('hide');
    }
}
function educ_submit(id,thisBtn,type,url){
    var sid = $('#employeeInformationModal input[name="id_no"]').val();
    var level = $('#educ-'+type+'-modal #level option:selected').val();
    var level_w = $('#educ-'+type+'-modal #level option:selected').data('w');
    var school = $('#educ-'+type+'-modal #school option:selected').val();
    var school_name = $('#educ-'+type+'-modal #school_name').val();
    var school_shorten = $('#educ-'+type+'-modal #school_shorten').val();
    var program = $('#educ-'+type+'-modal #program option:selected').val();
    var program_name = $('#educ-'+type+'-modal #program_name').val();
    var period_from = $('#educ-'+type+'-modal #period_from').val();
    var period_to = $('#educ-'+type+'-modal #period_to').val();
    var units_earned = $('#educ-'+type+'-modal #units_earned').val();
    var year_grad = $('#educ-'+type+'-modal #year_grad').val();

    var x_check = 0;
    var school_check = 0;
    var program_check = 0;
    var present_check = 0;

    if($('#educ-'+type+'-modal #school_check').is(':checked')){
        var school_check = 1;
    }
    if($('#educ-'+type+'-modal #program_check').is(':checked')){
        var program_check = 1;
    }
    if($('#educ-'+type+'-modal #present_check').is(':checked')){
        var present_check = 1;
    }

    $('#educ-'+type+'-modal .school_div').removeClass('border-require');
    $('#educ-'+type+'-modal .program_div').removeClass('border-require');
    $('#educ-'+type+'-modal #school_name').removeClass('border-require');
    $('#educ-'+type+'-modal #school_shorten').removeClass('border-require');
    $('#educ-'+type+'-modal #program_name').removeClass('border-require');
    $('#educ-'+type+'-modal #period_from').removeClass('border-require');
    $('#educ-'+type+'-modal #period_to').removeClass('border-require');

    if(school==0 && school_check==0){
        $('#educ-'+type+'-modal .school_div').addClass('border-require');
        x_check++;
    }
    if(program==0 && level_w=='w' && program_check==0){
        $('#educ-'+type+'-modal .program_div').addClass('border-require');
        x_check++;
    }
    if(school_check==1 && (school_name=='') && (school_shorten=='')){
        $('#educ-'+type+'-modal #school_name').addClass('border-require');
        $('#educ-'+type+'-modal #school_shorten').addClass('border-require');
        x_check++;
    }
    if(program_check==1 && (program_name=='')){
        $('#educ-'+type+'-modal #program_name').addClass('border-require');
        x_check++;
    }
    if(period_from==''){
        $('#educ-'+type+'-modal #period_from').addClass('border-require');
        x_check++;
    }
    if(present_check==0 && period_to==''){
        $('#educ-'+type+'-modal #period_to').addClass('border-require');
        x_check++;
    }
    if(x_check==0){
        var form_data = {
            sid:sid,
            id:id,
            level:level,
            school:school,
            school_name:school_name,
            school_shorten:school_shorten,
            program:program,
            program_name:program_name,
            period_from:period_from,
            period_to:period_to,
            units_earned:units_earned,
            year_grad:year_grad,
            school_check:school_check,
            program_check:program_check,
            present_check:present_check,
        };
        $.ajax({
            url: base_url+'/hrims/employee/'+url,
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
                    educ_table();
                    if(type=='new'){
                        $('#modal-info').modal('hide');
                    }
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
}
function educ_delete_submit(thisBtn){
    var fid = thisBtn.data('id');
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        fid:fid,
        id:id,
    };
    $.ajax({
        url: base_url+'/hrims/employee/educDeleteSubmit',
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
                educ_table();
                $('#modal-info').modal('hide');
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
function programSearch4(type){
    var level = $('#educ-'+type+'-modal #level option:selected').val();
    var school = $('#educ-'+type+'-modal #school option:selected').val();
    if(school>0){
        $(document).ready(function() {
            $(".programSearch2"+x).select2({
                dropdownParent: $("#programSearch2"),
                ajax: {
                url: base_url+'/search/programSearch2',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term,
                        level:level,
                        school:school
                    };
                },
                processResults: function (response) {
                    return {
                    results: response
                    };
                },
                cache: true
                }
            });
        });
    }
}
