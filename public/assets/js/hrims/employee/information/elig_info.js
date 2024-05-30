elig_table();
$(document).ready(function() {
    $(document).off('click', '#employeeInformationModal .doc-elig')
    .on('click', '#employeeInformationModal .doc-elig', function (e) {
        elig_doc($(this));
    });
    $(document).off('click', '#employeeInformationModal #new-elig')
    .on('click', '#employeeInformationModal #new-elig', function (e) {
        elig_new($(this));
    });
    $(document).off('click', '#elig-new-modal #submit')
    .on('click', '#elig-new-modal #submit', function (e) {
        elig_submit(0,$(this),'new','eligNewSubmit');
    });
    $(document).off('click', '#employeeInformationModal .edit-elig')
    .on('click', '#employeeInformationModal .edit-elig', function (e) {
        elig_edit($(this));
    });
    $(document).off('click', '#elig-edit-modal #submit')
    .on('click', '#elig-edit-modal #submit', function (e) {
        elig_submit($(this).data('id'),$(this),'edit','eligEditSubmit');
    });
    $(document).off('click', '#employeeInformationModal .delete-elig')
    .on('click', '#employeeInformationModal .delete-elig', function (e) {
        elig_delete($(this));
    });
    $(document).off('click', '#elig-delete-modal #submit')
    .on('click', '#elig-delete-modal #submit', function (e) {
        elig_delete_submit($(this));
    });
    $(document).off('click', '#elig-new-modal #elig_check')
        .on('click', '#elig-new-modal #elig_check', function (e) {
        eligNotList($(this),'new');
    });
    $(document).off('click', '#elig-edit-modal #elig_check')
        .on('click', '#elig-edit-modal #elig_check', function (e) {
        eligNotList($(this),'edit');
    });
    $(document).off('change', '#elig-new-modal #files')
    .on('change', '#elig-new-modal #files', function (e) {
        filesInfo($(this),'new');
    });
    $(document).off('change', '#elig-edit-modal #files')
    .on('change', '#elig-edit-modal #files', function (e) {
        filesInfo($(this),'edit');
    });
});
function elig_table(){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/eligTable',
        tid:'eligTable',
        id:id
    };
    loadTable(form_data);
}
function elig_new(thisBtn){
    var url = base_url+'/hrims/employee/eligNew';
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
function elig_doc(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/eligDoc';
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
function elig_edit(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/eligEdit';
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
function elig_delete(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/eligDelete';
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
function eligNotList(thisBtn,type){
    $('#elig-'+type+'-modal #elig_div').removeClass('hide');
    $('#elig-'+type+'-modal #elig_name').addClass('hide');
    $('#elig-'+type+'-modal #elig_shorten').addClass('hide');
    if(thisBtn.is(':checked')){
        $('#elig-'+type+'-modal #elig_div').addClass('hide');
        $('#elig-'+type+'-modal #elig_name').removeClass('hide');
        $('#elig-'+type+'-modal #elig_shorten').removeClass('hide');
    }
}
function filesInfo(thisBtn,type){
    var total_files = $('#elig-'+type+'-modal #files')[0].files.length;
    if(total_files==1){
        var file_selected_count = total_files+' file';
    }else{
        var file_selected_count = total_files+' files';
    }
    $('#elig-'+type+'-modal #file-selected-count').html(file_selected_count+' selected..');
}
function elig_submit(id,thisBtn,type,url){
    var sid = $('#employeeInformationModal input[name="id_no"]').val();
    var eligibility = $('#elig-'+type+'-modal #eligibility option:selected').val();
    var elig_name = $('#elig-'+type+'-modal #elig_name').val();
    var elig_shorten = $('#elig-'+type+'-modal #elig_shorten').val();
    var rating = $('#elig-'+type+'-modal #rating').val();
    var date = $('#elig-'+type+'-modal #date').val();
    var place = $('#elig-'+type+'-modal #place').val();
    var license_no = $('#elig-'+type+'-modal #license_no').val();
    var date_validity = $('#elig-'+type+'-modal #date_validity').val();
    var total_files = $('#elig-'+type+'-modal #files')[0].files.length;

    var x_check = 0;
    var elig_check = 0;

    $('#elig-'+type+'-modal #elig_div').removeClass('border-require');
    $('#elig-'+type+'-modal #elig_name').removeClass('border-require');
    $('#elig-'+type+'-modal #elig_shorten').removeClass('border-require');
    $('#elig-'+type+'-modal #rating').removeClass('border-require');
    $('#elig-'+type+'-modal #date').removeClass('border-require');
    $('#elig-'+type+'-modal #place').removeClass('border-require');
    $('#elig-'+type+'-modal #license_no').removeClass('border-require');

    if(rating==''){
        $('#elig-'+type+'-modal #rating').addClass('border-require');
        x_check++;
    }
    if(date==''){
        $('#elig-'+type+'-modal #date').addClass('border-require');
        x_check++;
    }
    if(place==''){
        $('#elig-'+type+'-modal #place').addClass('border-require');
        x_check++;
    }
    if(license_no==''){
        $('#elig-'+type+'-modal #license_no').addClass('border-require');
        x_check++;
    }
    if($('#elig-'+type+'-modal #elig_check').is(':checked')){
        var elig_check = 1;
    }
    if(elig_check==0 && eligibility==0){
        $('#elig-'+type+'-modal #elig_div').addClass('border-require');
        x_check++;
    }
    if(elig_check==1 && (elig_name=='') && (elig_shorten=='')){
        $('#elig-'+type+'-modal #elig_name').addClass('border-require');
        $('#elig-'+type+'-modal #elig_shorten').addClass('border-require');
        x_check++;
    }

    if(x_check==0){
        var form_data = new FormData();

        if(total_files>0){
            for (var x = 0; x < total_files; x++) {
                form_data.append('files'+x, $('#elig-'+type+'-modal #files')[0].files[x]);
            }
        }

        form_data.append('sid', sid);
        form_data.append('id', id);
        form_data.append('eligibility', eligibility);
        form_data.append('elig_name', elig_name);
        form_data.append('elig_shorten', elig_shorten);
        form_data.append('elig_check', elig_check);
        form_data.append('rating', rating);
        form_data.append('date', date);
        form_data.append('place', place);
        form_data.append('license_no', license_no);
        form_data.append('date_validity', date_validity);
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
                    elig_table();
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
function elig_delete_submit(thisBtn){
    var fid = thisBtn.data('id');
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        fid:fid,
        id:id,
    };
    $.ajax({
        url: base_url+'/hrims/employee/eligDeleteSubmit',
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
                elig_table();
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
