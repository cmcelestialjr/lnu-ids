fam_table();
$(document).ready(function() {
    $(document).off('click', '#employeeInformationModal #new-fam')
    .on('click', '#employeeInformationModal #new-fam', function (e) {
        fam_new($(this));
    });
    $(document).off('click', '#fam-new-modal #submit')
    .on('click', '#fam-new-modal #submit', function (e) {
        fam_submit(0,$(this),'new','familyNewSubmit');
    });
    $(document).off('click', '#employeeInformationModal .more-info-fam')
    .on('click', '#employeeInformationModal .more-info-fam', function (e) {
        fam_more_info($(this));
    });
    $(document).off('click', '#employeeInformationModal .edit-fam')
    .on('click', '#employeeInformationModal .edit-fam', function (e) {
        fam_edit($(this));
    });
    $(document).off('click', '#fam-edit-modal #submit')
    .on('click', '#fam-edit-modal #submit', function (e) {
        fam_submit($(this).data('id'),$(this),'edit','familyEditSubmit');
    });
    $(document).off('click', '#employeeInformationModal .delete-fam')
    .on('click', '#employeeInformationModal .delete-fam', function (e) {
        fam_delete($(this));
    });
    $(document).off('click', '#fam-delete-modal #submit')
    .on('click', '#fam-delete-modal #submit', function (e) {
        fam_delete_submit($(this));
    });
});
function fam_table(){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/familyTable',
        tid:'familyTable',
        id:id
    };
    loadTable(form_data);
}
function fam_new(thisBtn){
    var url = base_url+'/hrims/employee/familyNew';
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
function fam_more_info(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/familyMoreInfo';
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
function fam_edit(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/familyEdit';
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
function fam_delete(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/familyDelete';
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
function fam_submit(id,thisBtn,type,url){
    var sid = $('#employeeInformationModal input[name="id_no"]').val();
    var relation = $('#fam-'+type+'-modal #relation option:selected').val();
    var lastname = $('#fam-'+type+'-modal #lastname').val();
    var firstname = $('#fam-'+type+'-modal #firstname').val();
    var middlename = $('#fam-'+type+'-modal #middlename').val();
    var extname = $('#fam-'+type+'-modal #extname').val();
    var dob = $('#fam-'+type+'-modal #dob').val();
    var contact_no = $('#fam-'+type+'-modal #contact_no').val();
    var email = $('#fam-'+type+'-modal #email').val();
    var occupation = $('#fam-'+type+'-modal #occupation').val();
    var employer = $('#fam-'+type+'-modal #employer').val();
    var employer_contact = $('#fam-'+type+'-modal #employer_contact').val();
    var employer_address = $('#fam-'+type+'-modal #employer_address').val();

    var x_check = 0;
    $('#fam-'+type+'-modal #lastname').removeClass('border-require');
    $('#fam-'+type+'-modal #firstname').removeClass('border-require');
    $('#fam-'+type+'-modal #dob').removeClass('border-require');

    if(lastname==''){
        $('#fam-'+type+'-modal #lastname').addClass('border-require');
        x_check++;
    }
    if(firstname==''){
        $('#fam-'+type+'-modal #firstname').addClass('border-require');
        x_check++;
    }
    if(dob==''){
        $('#fam-'+type+'-modal #dob').addClass('border-require');
        x_check++;
    }

    if(x_check==0){
        var form_data = {
            sid:sid,
            id:id,
            relation:relation,
            lastname:lastname,
            firstname:firstname,
            middlename:middlename,
            extname:extname,
            dob:dob,
            contact_no:contact_no,
            email:email,
            occupation:occupation,
            employer:employer,
            employer_contact:employer_contact,
            employer_address:employer_address,
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
                    fam_table();
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
function fam_delete_submit(thisBtn){
    var fid = thisBtn.data('id');
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        fid:fid,
        id:id,
    };
    $.ajax({
        url: base_url+'/hrims/employee/familyDeleteSubmit',
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
                fam_table();
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
