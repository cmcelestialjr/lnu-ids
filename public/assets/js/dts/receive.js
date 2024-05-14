function receiveDoc(id,thisBtn){
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/dts/receive',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {

        },
        success : function(data){
            if(data.result=='success'){
                thisBtn.removeClass('fa fa-caret-square-o-down');
                thisBtn.removeClass('btn btn-primary btn-primary-scan');
                thisBtn.addClass('fa fa-forward');
                thisBtn.addClass('btn btn-info btn-info-scan');
                thisBtn.attr('title','Forward');
                thisBtn.data('o','Forward');
                thisBtn.closest('tr').find('.latest_action').html(data.latest_action);
                toastr.success('Success!');
            }else{
                toastr.error('Error!');
            }
        },
        error: function (){

        }
    });
}
