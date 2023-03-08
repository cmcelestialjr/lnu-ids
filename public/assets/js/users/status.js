$(document).on('click', '.status', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/users/statusView';    
    var modal = 'default';
    var id = thisBtn.data('id');
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        statis:'',
        w_table:'wo',
        url_table:'',
        tid:'',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#statusDiv button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#statusDiv input[name="id"]').val();
    var status = $('#statusDiv select[name="status"] option:selected').val();
    var form_data = {
        id:id,
        status:status
    };
    $.ajax({
        url: base_url+'/users/statusUpdate',
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
                viewTableLoad();
            }else{
                toastr.error('Error.');
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
});

$(document).on('click', '#statusDiv button[name="submit"]', function (e) {
    
});