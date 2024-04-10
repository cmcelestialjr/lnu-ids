loadsheet();
$(document).off('change', '#loadSheet select[name="school_year"]').on('change', '#loadSheet select[name="school_year"]', function (e) {
    loadsheet($(this));
});
function loadsheet(thisBtn){
    var thisBtn = $('#loader');
    var school_year = $('select[name="school_year"] option:selected').val();
    var form_data = {
        school_year:school_year
    };
    $.ajax({
        url: base_url+'/fis/loadSheet',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.html('<center>'+skeletonLoader('')+'</center>');
            $('#documentPreview').addClass('hide');
        },
        success : function(data){
            thisBtn.html('');
            $('#documentPreview').removeClass('hide');
            if(data.result=='success'){
                $('#documentPreview').attr('src', data.src+'#zoom=80');
            }
        },
        error: function (){
            thisBtn.html('');
        }
    });
}
