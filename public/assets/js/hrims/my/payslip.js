payslip();
$(document).off('click', 'button[name="submit"]').on('click', 'button[name="submit"]', function (e) {    
    payslip();
});
function payslip(){
    var thisBtn = $('button[name="submit"]');
    var payroll_type = $('select[name="payroll_type"] option:selected').val();
    var year = $('select[name="year"] option:selected').val();
    var month = $('select[name="month"] option:selected').val();
    var form_data = {
        payroll_type:payroll_type,
        year:year,
        month:month
    };
    $.ajax({
        url: base_url+'/hrims/my/payslip',
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
            $('#documentPreviewDiv').addClass('opacity6');
        },
        success : function(data){            
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            $('#documentPreviewDiv').removeClass('opacity6');
            if(data.result=='success'){
                toastr.success('Success');
                thisBtn.addClass('input-success');
            }else{
                toastr.error(data.result);
                thisBtn.addClass('input-error');
            }
            $('#documentPreview').attr('src', data.src);

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
            $('#documentPreviewDiv').removeAttr('disabled');
        }
    });
}