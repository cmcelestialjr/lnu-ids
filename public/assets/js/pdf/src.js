var base_url = window.location.origin;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
src();
function src(){    
    var pdf_option = $('#pdf_option').val();
    var thisBtn = $('#loader');    
    var form_data = {
        pdf_option:pdf_option
    };
    $.ajax({
        url: base_url+'/pdf/src',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.html('<center>'+loadingTemplate('')+'</center>');
            $('#documentPreview').addClass('hide');
        },
        success : function(data){
            thisBtn.html('');
            $('#documentPreview').removeClass('hide');
            if(data.result=='success'){
                $('#documentPreview').attr('src', data.src+'#zoom=80');
                $('#documentPreview').css('height', $(window).height());
            }else{
                thisBtn.addClass('input-error');
            }
        },
        error: function (){
            thisBtn.html('');
        }
    });
}
function loadingTemplate() {
    var img = base_url+"/assets/images/loader/loader_gif_no_bg.gif";
    return '<span class="loading-wrap"><img src="'+img+'" alt="Loader" class="loaderTable"></span>';
}