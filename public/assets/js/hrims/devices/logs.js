$(document).off('click', '.logsAcquire').on('click', '.logsAcquire', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var status = thisBtn.data('s');
    var x = 0;
    if(status!='On'){
        toastr.error('This device is off. Please turn it on first.');
        x++;
    }
    var form_data = {
        id:id
    };
    if(x==0){
        $.ajax({
            url: base_url+'/hrims/devices/logsAcquire',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                $('.logsAcquire').attr('disabled','disabled'); 
                thisBtn.addClass('input-loading');
            },
            success : function(data){
                $('.logsAcquire').removeAttr('disabled');
                thisBtn.removeClass('input-loading');
                if(data.result=='success'){
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
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
                $('.logsAcquire').removeAttr('disabled');
                thisBtn.removeClass('input-success');
                thisBtn.removeClass('input-error');
            }
        });
    }
});