other_skill_table();
other_recognition_table();
other_organization_table();
$(document).ready(function() {
    $(document).off('click', '#employeeInformationModal #new-other')
    .on('click', '#employeeInformationModal #new-other', function (e) {
        other_new($(this));
    });
    $(document).off('click', '#other-new-modal #submit')
    .on('click', '#other-new-modal #submit', function (e) {
        other_submit(0,$(this),'new','otherNewSubmit');
    });
    $(document).off('click', '#employeeInformationModal .edit-other')
    .on('click', '#employeeInformationModal .edit-other', function (e) {
        other_edit($(this));
    });
    $(document).off('click', '#other-edit-modal #submit')
    .on('click', '#other-edit-modal #submit', function (e) {
        other_submit($(this).data('id'),$(this),'edit','otherEditSubmit');
    });
    $(document).off('click', '#employeeInformationModal .delete-other')
    .on('click', '#employeeInformationModal .delete-other', function (e) {
        other_delete($(this));
    });
    $(document).off('click', '#other-delete-modal #submit')
    .on('click', '#other-delete-modal #submit', function (e) {
        other_delete_submit($(this));
    });
});
function other_skill_table(){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/otherSkillTable',
        tid:'otherSkillTable',
        id:id
    };
    loadTable(form_data);
}
function other_recognition_table(){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/otherRecognitionTable',
        tid:'otherRecognitionTable',
        id:id
    };
    loadTable(form_data);
}
function other_organization_table(){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/otherOrganizationTable',
        tid:'otherOrganizationTable',
        id:id
    };
    loadTable(form_data);
}
function other_new(thisBtn){
    var url = base_url+'/hrims/employee/otherNew';
    var modal = 'info';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModal(form_data,thisBtn);
}
function other_edit(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var option = thisBtn.data('o');
    var url = base_url+'/hrims/employee/otherEdit';
    var modal = 'info';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        fid:fid,
        option:option
    };
    loadModal(form_data,thisBtn);
}
function other_delete(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var option = thisBtn.data('o');
    var url = base_url+'/hrims/employee/otherDelete';
    var modal = 'info';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        fid:fid,
        option:option
    };
    loadModal(form_data,thisBtn);
}
function other_submit(id,thisBtn,type,url){
    var sid = $('#employeeInformationModal input[name="id_no"]').val();
    var name = $('#other-'+type+'-modal #name').val();
    var option = $('#other-'+type+'-modal #option option:selected').val();

    var x_check = 0;

    $('#other-'+type+'-modal #name').removeClass('border-require');

    if(name==''){
        $('#other-'+type+'-modal #name').addClass('border-require');
        x_check++;
    }

    if(x_check==0){
        var form_data = {
            sid:sid,
            id:id,
            name:name,
            option:option,
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

                    if(option=='skills'){
                        other_skill_table();
                    }else if(option=='recognition'){
                        other_recognition_table();
                    }else{
                        other_organization_table();
                    }

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
function other_delete_submit(thisBtn){
    var fid = thisBtn.data('id');
    var option = thisBtn.data('o');
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        fid:fid,
        id:id,
        option:option
    };
    $.ajax({
        url: base_url+'/hrims/employee/otherDeleteSubmit',
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
                if(option=='skills'){
                    other_skill_table();
                }else if(option=='recognition'){
                    other_recognition_table();
                }else{
                    other_organization_table();
                }
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
