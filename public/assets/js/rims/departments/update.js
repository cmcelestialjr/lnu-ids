$(document).on('click', '#editModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#editModal input[name="id"]').val();
    var name = $('#editModal input[name="name"]').val();
    var shorten = $('#editModal input[name="shorten"]').val();
    var code = $('#editModal input[name="code"]').val();
    var x = 0;
    if(name==''){
        $('#editModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(shorten==''){
        $('#editModal input[name="shorten"]').addClass('border-require');
        x++;
    }
    if(code==''){
        $('#editModal input[name="code"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            name:name,
            shorten:shorten,
            code:code
        };
        $.ajax({
            url: base_url+'/rims/departments/editModalSubmit',
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
                    view_departments();
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
    }
});