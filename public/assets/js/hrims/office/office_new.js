$(document).off('click', '#officeDiv .new').on('click', '#officeDiv .new', function (e) {
    office_new($(this));
});
$(document).off('click', '#officeNew button[name="submit"]').on('click', '#officeNew button[name="submit"]', function (e) {
    office_new_submit($(this));
});
function office_new(thisBtn){
    var url = base_url+'/hrims/office/officeNew';
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
function office_new_submit(thisBtn){
    var name = $('#officeNew input[name="name"]').val();
    var shorten = $('#officeNew input[name="shorten"]').val();
    var office_type = $('#officeNew select[name="office_type"] option:selected').val();
    var parent_office = $('#officeNew select[name="parent_office"] option:selected').val();
    var form_data = {
        name:name,
        shorten:shorten,
        office_type:office_type,
        parent_office:parent_office
    };
    $.ajax({
        url: base_url+'/hrims/office/officeNewSubmit',
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