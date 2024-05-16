$(document).off('click', '#officeDiv .update').on('click', '#officeDiv .update', function (e) {
    office_update($(this));
});
$(document).off('click', '#officeUpdate button[name="submit"]').on('click', '#officeUpdate button[name="submit"]', function (e) {
    office_update_submit($(this));
});
function office_update(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/office/officeUpdate/'+id;
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
}
function office_update_submit(thisBtn){
    var id = thisBtn.data('id');
    var name = $('#officeUpdate input[name="name"]').val();
    var shorten = $('#officeUpdate input[name="shorten"]').val();
    var office_type = $('#officeUpdate select[name="office_type"] option:selected').val();
    var parent_office = $('#officeUpdate select[name="parent_office"] option:selected').val();
    var form_data = {
        id:id,
        name:name,
        shorten:shorten,
        office_type:office_type,
        parent_office:parent_office
    };
    $.ajax({
        url: base_url+'/hrims/office/officeUpdateSubmit/'+id,
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
                office_table();
                $('#modal-default').modal('hide');
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
