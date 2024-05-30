volun_table();
$(document).ready(function() {
    $(document).off('click', '#employeeInformationModal .doc-volun')
    .on('click', '#employeeInformationModal .doc-volun', function (e) {
        volun_doc($(this));
    });
    $(document).off('click', '#employeeInformationModal #new-volun')
    .on('click', '#employeeInformationModal #new-volun', function (e) {
        volun_new($(this));
    });
    $(document).off('click', '#volun-new-modal #submit')
    .on('click', '#volun-new-modal #submit', function (e) {
        volun_submit(0,$(this),'new','volunNewSubmit');
    });
    $(document).off('click', '#employeeInformationModal .edit-volun')
    .on('click', '#employeeInformationModal .edit-volun', function (e) {
        volun_edit($(this));
    });
    $(document).off('click', '#volun-edit-modal #submit')
    .on('click', '#volun-edit-modal #submit', function (e) {
        volun_submit($(this).data('id'),$(this),'edit','volunEditSubmit');
    });
    $(document).off('click', '#employeeInformationModal .delete-volun')
    .on('click', '#employeeInformationModal .delete-volun', function (e) {
        volun_delete($(this));
    });
    $(document).off('click', '#volun-delete-modal #submit')
    .on('click', '#volun-delete-modal #submit', function (e) {
        volun_delete_submit($(this));
    });
    $(document).off('change', '#volun-new-modal #files')
    .on('change', '#volun-new-modal #files', function (e) {
        filesInfo($(this),'new');
    });
    $(document).off('change', '#volun-edit-modal #files')
    .on('change', '#volun-edit-modal #files', function (e) {
        filesInfo($(this),'edit');
    });
    $(document).off('click', '#volun-new-modal #present_check')
    .on('click', '#volun-new-modal #present_check', function (e) {
        periodToPresent($(this),'new');
    });
    $(document).off('click', '#volun-edit-modal #present_check')
    .on('click', '#volun-edit-modal #present_check', function (e) {
        periodToPresent($(this),'edit');
    });
});
function volun_table(){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/volunTable',
        tid:'volunTable',
        id:id
    };
    loadTable(form_data);
}
function volun_new(thisBtn){
    var url = base_url+'/hrims/employee/volunNew';
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
function volun_doc(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/volunDoc';
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
function volun_edit(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/volunEdit';
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
function volun_delete(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/volunDelete';
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
    $('#volun-'+type+'-modal #date_to').prop('readonly', false);
    if(thisBtn.is(':checked')){
        $('#volun-'+type+'-modal #date_to').prop('readonly', true);
    }
}
function filesInfo(thisBtn,type){
    var total_files = $('#volun-'+type+'-modal #files')[0].files.length;
    if(total_files==1){
        var file_selected_count = total_files+' file';
    }else{
        var file_selected_count = total_files+' files';
    }
    $('#volun-'+type+'-modal #file-selected-count').html(file_selected_count+' selected..');
}
function volun_submit(id,thisBtn,type,url){
    var sid = $('#employeeInformationModal input[name="id_no"]').val();
    var name = $('#volun-'+type+'-modal #name').val();
    var date_from = $('#volun-'+type+'-modal #date_from').val();
    var date_to = $('#volun-'+type+'-modal #date_to').val();
    var hours = $('#volun-'+type+'-modal #hours').val();
    var position = $('#volun-'+type+'-modal #position').val();
    var total_files = $('#volun-'+type+'-modal #files')[0].files.length;

    var x_check = 0;
    var present_check = 0;

    $('#volun-'+type+'-modal #name').removeClass('border-require');
    $('#volun-'+type+'-modal #date_from').removeClass('border-require');
    $('#volun-'+type+'-modal #date_to').removeClass('border-require');
    $('#volun-'+type+'-modal #hours').removeClass('border-require');
    $('#volun-'+type+'-modal #position').removeClass('border-require');


    if(name==''){
        $('#volun-'+type+'-modal #name').addClass('border-require');
        x_check++;
    }
    if(date_from==''){
        $('#volun-'+type+'-modal #date_from').addClass('border-require');
        x_check++;
    }
    if(hours==''){
        $('#volun-'+type+'-modal #hours').addClass('border-require');
        x_check++;
    }
    if(position==''){
        $('#volun-'+type+'-modal #position').addClass('border-require');
        x_check++;
    }
    if($('#volun-'+type+'-modal #present_check').is(':checked')){
        var present_check = 1;
    }

    if(present_check==0 && date_to==''){
        $('#volun-'+type+'-modal #date_to').addClass('border-require');
    }

    if(x_check==0){
        var form_data = new FormData();

        if(total_files>0){
            for (var x = 0; x < total_files; x++) {
                form_data.append('files'+x, $('#volun-'+type+'-modal #files')[0].files[x]);
            }
        }

        form_data.append('sid', sid);
        form_data.append('id', id);
        form_data.append('name', name);
        form_data.append('date_from', date_from);
        form_data.append('date_to', date_to);
        form_data.append('present_check', present_check);
        form_data.append('hours', hours);
        form_data.append('position', position);
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
                    volun_table();
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
function volun_delete_submit(thisBtn){
    var fid = thisBtn.data('id');
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        fid:fid,
        id:id,
    };
    $.ajax({
        url: base_url+'/hrims/employee/volunDeleteSubmit',
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
                volun_table();
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
