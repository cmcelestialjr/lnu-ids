loadTableSignature();
$(document).off('change', '#signatory select[name="type"]').on('change', '#signatory select[name="type"]', function (e) {
    loadTableSignature();
});
$(document).off('click', '#signatory .signatoryModal').on('click', '#signatory .signatoryModal', function (e) {
    var thisBtn = $(this);
    signatoryModal(thisBtn);
});
$(document).off('click', '#signatoryUpdate button[name="submit"]').on('click', '#signatoryUpdate button[name="submit"]', function (e) {
    var thisBtn = $(this);
    signatorySubmit(thisBtn);
});
function loadTableSignature(){
    var thisBtn = $('#signatory select[name="type"]');
    var type = $('#signatory select[name="type"] option:selected').val();
    var form_data = {
        url_table:base_url+'/signatory/table',
        tid:'signatoryTable',
        type:type
    };
    loadTablewLoader(form_data,thisBtn);
}
function signatoryModal(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/signatory/modal';
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function signatorySubmit(thisBtn){
    var id = thisBtn.data('id');
    var signatory = $('#signatoryUpdate select[name="signatory"] option:selected').val();
    // if(signatory==''){
    //     $('#signatoryUpdate #employeeSearch').addClass('border-require');
    //     toastr.error('Please select Name for signatory.');
    // }else{
        var form_data = {
            signatory:signatory,
            id:id
        };        
        $.ajax({
            url: base_url+'/signatory/update',
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
                    toastr.success('Success!');
                    thisBtn.addClass('input-success');
                    $('#modal-default').modal('hide');
                    $('#signatoryType'+id).html(data.signatory_name);
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
    //}
}