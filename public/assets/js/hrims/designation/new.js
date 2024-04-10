$(document).off('click', '#designationNew').on('click', '#designationNew', function (e) {
    designationNew($(this));
});
$(document).off('click', '#designationNewModal button[name="submit"]').on('click', '#designationNewModal button[name="submit"]', function (e) {
    designationNewSubmit($(this));
});
function designationNew(thisBtn){
    var url = base_url+'/hrims/designation/new';
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
function designationNewSubmit(thisBtn){
    var name = $('#designationNewModal input[name="name"]').val();
    var shorten = $('#designationNewModal input[name="shorten"]').val();
    var level = $('#designationNewModal input[name="level"]').val();
    var office = $('#designationNewModal select[name="office"] option:selected').val();
    var form_data = {
        name:name,
        shorten:shorten,
        level:level,
        office:office
    };
    $.ajax({
        url: base_url+'/hrims/designation/newSubmit',
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