$(document).off('click', 'button[name="submit_new_employee"]').on('click', 'button[name="submit_new_employee"]', function (e) {
    var thisBtn = $(this);
    var x = 0;
    var lastname = $('input[name="lastname"]').val();
    var firstname = $('input[name="firstname"]').val();
    var middlename = $('input[name="middlename"]').val();
    var extname = $('input[name="extname"]').val();
    var dob = $('input[name="dob"]').val();
    var sex = $('select[name="sex"] option:selected').val();
    var civil_status = $('select[name="civil_status"] option:selected').val();
    var email = $('input[name="email"]').val();
    var contact_no = $('input[name="contact_no"]').val();
    var date_from = $('input[name="date_from"]').val();
    var date_to_option = $('input[name="date_to_option"]').val();
    var date_to = $('input[name="date_to"]').val();
    var position_id = $('select[name="position_id"] option:selected').val();
    var position_title = $('input[name="position_title"]').val();
    var position_shorten = $('input[name="position_shorten"]').val();
    var salary = $('input[name="salary"]').val();
    var sg = $('input[name="sg"]').val();
    var step = $('input[name="step"]').val();    
    var emp_stat = $('select[name="emp_stat"] option:selected').val();
    var fund_source = $('select[name="fund_source"] option:selected').val();
    var fund_services = $('select[name="fund_services"] option:selected').val();
    var gov_service = $('select[name="gov_service"] option:selected').val();
    var designation = $('select[name="designation"] option:selected').val();
    var credit_type = $('select[name="credit_type"] option:selected').val();
    var role = $('select[name="role"] option:selected').val();
    $('input[name="lastname"]').removeClass('border-require');
    $('input[name="firstname"]').removeClass('border-require');
    $('input[name="date_from"]').removeClass('border-require');
    $('input[name="date_to"]').removeClass('border-require');
    $('input[name="salary"]').removeClass('border-require');
    $('input[name="sg"]').removeClass('border-require');
    $('input[name="step"]').removeClass('border-require');
    if(lastname==''){
        $('input[name="lastname"]').addClass('border-require');
        toastr.error('Please input Lastname');
        x++;
    }    
    if(firstname==''){
        $('input[name="firstname"]').addClass('border-require');
        toastr.error('Please input Firstname');
        x++;
    }    
    if(date_from==''){
        $('input[name="date_from"]').addClass('border-require');
        toastr.error('Please input Date hired');
        x++;
    }
    if(date_to_option=='date'){
        if(date_to==''){
            $('input[name="date_to"]').addClass('border-require');
            toastr.error('Please input Date To');
            x++;
        }
    }    
    if(salary==''){
        $('input[name="salary"]').addClass('border-require');
        toastr.error('Please input Salary');
        x++;
    }    
    if(sg==''){
        $('input[name="sg"]').addClass('border-require');
        toastr.error('Please input SG');
        x++;
    }    
    if(step==''){
        $('input[name="step"]').addClass('border-require');
        toastr.error('Please input Step');
        x++;
    }
    if(x==0){
        var form_data = {
            lastname:lastname,
            firstname:firstname,
            middlename:middlename,
            extname:extname,
            dob:dob,
            sex:sex,
            civil_status:civil_status,
            email:email,
            contact_no:contact_no,
            date_from:date_from,
            date_to_option:date_to_option,
            date_to:date_to,
            position_id:position_id,
            position_title:position_title,
            position_shorten:position_shorten,
            salary:salary,
            sg:sg,
            step:step,
            emp_stat:emp_stat,
            fund_source:fund_source,
            fund_services:fund_services,
            gov_service:gov_service,
            designation:designation,
            credit_type:credit_type,
            role:role,
        };
        $.ajax({
            url: base_url+'/hrims/employee/employeeNew',
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
                    $('input[name="lastname"]').val('');
                    $('input[name="firstname"]').val('');
                    $('input[name="middlename"]').val('');
                    $('input[name="extname"]').val('');
                    $('input[name="dob"]').val('');
                    $('input[name="email"]').val('');
                    $('input[name="contact_no"]').val('');
                    $('input[name="date_from"]').val('');
                    $('input[name="date_to_option"]').val('');
                    $('input[name="date_to"]').val('');
                    $('select[name="position_id"]').empty();
                    $('input[name="position_title"]').val('');
                    $('input[name="position_shorten"]').val('');
                    $('input[name="salary"]').val('');
                    $('input[name="sg"]').val('');
                    $('input[name="step"]').val('');
                }else if(data.result=='error'){
                    toastr.error('Error.');
                    thisBtn.addClass('input-error');
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
});