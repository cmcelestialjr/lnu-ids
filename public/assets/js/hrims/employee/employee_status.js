employee_details();
$(document).off('change', '#statusModal select[name="status"]').on('change', '#statusModal select[name="status"]', function (e) {
    employee_details();   
});
$(document).off('click', '#statusModal button[name="submit"]').on('click', '#statusModal button[name="submit"]', function (e) {
    employee_status_submit();   
});
function employee_details(){
    var status = $('#statusModal select[name="status"] option:selected').val();
    $('#statusModal #separationDetails').addClass('hide');
    if(status==2){
        $('#statusModal #separationDetails').removeClass('hide');
    }
}
function employee_status_submit(){
    var thisBtn = $('#statusModal button[name="submit"]')
    var id = $('#statusModal input[name="id"]').val();
    var status = $('#statusModal select[name="status"] option:selected').val();
    var cause = $('#statusModal input[name="cause"]').val();
    var separation_date = $('#statusModal input[name="separation_date"]').val();
    var x = 0;
    $('#statusModal input[name="cause"]').removeClass('border-require');
    $('#statusModal input[name="separation_date"]').removeClass('border-require');
    if(status==2){
        if(cause==''){
            toastr.error('Please input Separation Cause');
            $('#statusModal input[name="cause"]').addClass('border-require');
            x++;
        }
        if(separation_date==''){
            toastr.error('Please input Separation Date');
            $('#statusModal input[name="separation_date"]').addClass('border-require');
            x++;
        }
    }
    if(x==0){
        var form_data = {
            id:id,
            status:status,
            cause:cause,
            separation_date:separation_date
        };
        $.ajax({
            url: base_url+'/hrims/employee/employeeStatusSubmit',
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
                    $('#employeeViewModal #employee_status').removeClass('btn-success btn-success-scan');
                    $('#employeeViewModal #employee_status').removeClass('btn-danger btn-danger-scan');
                    $('#employeeViewModal #employee_status').html('');
                    $('#employeeViewModal #employee_status').addClass(data.class);
                    $('#employeeViewModal #employee_status').html(data.html);
                    $('#modal-primary').modal('hide');
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
}