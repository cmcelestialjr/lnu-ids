learn_table();
$(document).ready(function() {
    $(document).off('click', '#employeeInformationModal .doc-learn')
    .on('click', '#employeeInformationModal .doc-learn', function (e) {
        learn_doc($(this));
    });
    $(document).off('click', '#employeeInformationModal #new-learn')
    .on('click', '#employeeInformationModal #new-learn', function (e) {
        learn_new($(this));
    });
    $(document).off('click', '#learn-new-modal #submit')
    .on('click', '#learn-new-modal #submit', function (e) {
        learn_submit(0,$(this),'new','learnNewSubmit');
    });
    $(document).off('click', '#employeeInformationModal .edit-learn')
    .on('click', '#employeeInformationModal .edit-learn', function (e) {
        learn_edit($(this));
    });
    $(document).off('click', '#learn-edit-modal #submit')
    .on('click', '#learn-edit-modal #submit', function (e) {
        learn_submit($(this).data('id'),$(this),'edit','learnEditSubmit');
    });
    $(document).off('click', '#employeeInformationModal .delete-learn')
    .on('click', '#employeeInformationModal .delete-learn', function (e) {
        learn_delete($(this));
    });
    $(document).off('click', '#learn-delete-modal #submit')
    .on('click', '#learn-delete-modal #submit', function (e) {
        learn_delete_submit($(this));
    });
    $(document).off('change', '#learn-new-modal #files')
    .on('change', '#learn-new-modal #files', function (e) {
        filesInfo($(this),'new');
    });
    $(document).off('change', '#learn-edit-modal #files')
    .on('change', '#learn-edit-modal #files', function (e) {
        filesInfo($(this),'edit');
    });
    $(document).off('click', '#learn-new-modal #type_check')
    .on('click', '#learn-new-modal #type_check', function (e) {
        typeName($(this),'new');
    });
    $(document).off('click', '#learn-edit-modal #type_check')
    .on('click', '#learn-edit-modal #type_check', function (e) {
        typeName($(this),'edit');
    });
});
function learn_table(){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/learnTable',
        tid:'learnTable',
        id:id
    };
    loadTable(form_data);
}
function learn_new(thisBtn){
    var url = base_url+'/hrims/employee/learnNew';
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
function learn_doc(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/learnDoc';
    var modal = 'success';
    var modal_size = 'modal-xl';
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
function learn_edit(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/learnEdit';
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
function learn_delete(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/learnDelete';
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
function typeName(thisBtn,type){
    $('#learn-'+type+'-modal #type_div').removeClass('hide');
    $('#learn-'+type+'-modal #type_name').addClass('hide');
    if(thisBtn.is(':checked')){
        $('#learn-'+type+'-modal #type_div').addClass('hide');
        $('#learn-'+type+'-modal #type_name').removeClass('hide');
    }
}
function filesInfo(thisBtn,type){
    var total_files = $('#learn-'+type+'-modal #files')[0].files.length;
    if(total_files==1){
        var file_selected_count = total_files+' file';
    }else{
        var file_selected_count = total_files+' files';
    }
    $('#learn-'+type+'-modal #file-selected-count').html(file_selected_count+' selected..');
}
function learn_submit(id,thisBtn,type,url){
    var sid = $('#employeeInformationModal input[name="id_no"]').val();
    var name = $('#learn-'+type+'-modal #name').val();
    var date_from = $('#learn-'+type+'-modal #date_from').val();
    var date_to = $('#learn-'+type+'-modal #date_to').val();
    var hours = $('#learn-'+type+'-modal #hours').val();
    var type1 = $('#learn-'+type+'-modal #type option:selected').val();
    var type_name = $('#learn-'+type+'-modal #type_name').val();
    var conducted_by = $('#learn-'+type+'-modal #conducted_by').val();
    var total_files = $('#learn-'+type+'-modal #files')[0].files.length;

    var x_check = 0;
    var type_check = 0;

    $('#learn-'+type+'-modal #name').removeClass('border-require');
    $('#learn-'+type+'-modal #date_from').removeClass('border-require');
    $('#learn-'+type+'-modal #date_to').removeClass('border-require');
    $('#learn-'+type+'-modal #hours').removeClass('border-require');
    $('#learn-'+type+'-modal #type_name').removeClass('border-require');
    $('#learn-'+type+'-modal #conducted_by').removeClass('border-require');
    $('#learn-'+type+'-modal #type_div').removeClass('border-require');

    if(name==''){
        $('#learn-'+type+'-modal #name').addClass('border-require');
        x_check++;
    }
    if(date_from==''){
        $('#learn-'+type+'-modal #date_from').addClass('border-require');
        x_check++;
    }
    if(date_to==''){
        $('#learn-'+type+'-modal #date_to').addClass('border-require');
        x_check++;
    }
    if(hours==''){
        $('#learn-'+type+'-modal #hours').addClass('border-require');
        x_check++;
    }
    if(conducted_by==''){
        $('#learn-'+type+'-modal #conducted_by').addClass('border-require');
        x_check++;
    }
    if($('#learn-'+type+'-modal #type_check').is(':checked')){
        var type_check = 1;
    }

    if(type1==0 && type_check==0){
        $('#learn-'+type+'-modal #type_div').addClass('border-require');
        x_check++;
    }

    if(type_check==1 && type_name==''){
        $('#learn-'+type+'-modal #type_name').addClass('border-require');
        x_check++;
    }

    if(x_check==0){
        var form_data = new FormData();

        if(total_files>0){
            for (var x = 0; x < total_files; x++) {
                form_data.append('files'+x, $('#learn-'+type+'-modal #files')[0].files[x]);
            }
        }

        form_data.append('sid', sid);
        form_data.append('id', id);
        form_data.append('name', name);
        form_data.append('date_from', date_from);
        form_data.append('date_to', date_to);
        form_data.append('hours', hours);
        form_data.append('type', type1);
        form_data.append('type_name', type_name);
        form_data.append('type_check', type_check);
        form_data.append('conducted_by', conducted_by);
        form_data.append('total_files', total_files);

        $.ajax({
            url: base_url+'/hrims/employee/'+url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            contentType: false,
            processData: false,
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
                    learn_table();
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
function learn_delete_submit(thisBtn){
    var fid = thisBtn.data('id');
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        fid:fid,
        id:id,
    };
    $.ajax({
        url: base_url+'/hrims/employee/learnDeleteSubmit',
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
                learn_table();
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
