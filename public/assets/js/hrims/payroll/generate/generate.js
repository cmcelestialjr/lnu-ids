$(document).off('change', '#generateDiv select[name="emp_stat[]"]').on('change', '#generateDiv select[name="emp_stat[]"]', function (e) {
    show_hide_div();
});
$(document).off('change', '#generateDiv select[name="option"]').on('change', '#generateDiv select[name="option"]', function (e) {
    show_hide_div();
});
$(document).off('change', '#generateDiv select[name="payroll_type"]').on('change', '#generateDiv select[name="payroll_type"]', function (e) {
    var val = $(this).val();
    show_hide_div();
});
$(document).off('click', '#generateDiv button[name="submit"]').on('click', '#generateDiv button[name="submit"]', function (e) {    
    generate_list();
});
$(document).off('click', '#generateDiv .generate').on('click', '#generateDiv .generate', function (e) {
    var thisBtn = $(this);
    generate(thisBtn);
});
$(document).on('click', '#listTableDiv #all', function (e) {
    if (this.checked) {
        $('#listTableDiv .employee').prop('checked', true);
    }else{
        $('#listTableDiv .employee').prop('checked', false);
    }
});
function show_hide_div(){
    var emp_stats = [];
    var emp_stats_g = [];
    var payroll_type = $('#generateDiv select[name="payroll_type"] option:selected').val();
    var option = $('#generateDiv select[name="option"] option:selected').val();    
    $('#generateDiv select[name="emp_stat[]"] option:selected').each(function() {
        emp_stats.push($(this).val());
        emp_stats_g.push($(this).data('g'));
    });
    if(payroll_type<=2){
        $('#generateDiv #monthSingleDiv').removeClass('hide');
        $('#generateDiv #monthMultipleDiv').addClass('hide');
        $('#generateDiv #unclaimedDiv').addClass('hide');
        $('#generateDiv #include_peraDiv').removeClass('hide');        
        if(jQuery.inArray('5', emp_stats) != -1){
            $('#generateDiv #monthMultipleDiv').removeClass('hide');
            $('#generateDiv #unclaimedDiv').removeClass('hide');
            $('#generateDiv #monthSingleDiv').addClass('hide');
            $('#generateDiv #optionSelectDiv').addClass('hide');
            $('#generateDiv #durationDiv').addClass('hide');
            $('#generateDiv #optionDiv').addClass('hide');
            $('#generateDiv #include_peraDiv').addClass('hide');
        }else{
            $('#generateDiv #optionSelectDiv').addClass('hide');
            $('#generateDiv #durationDiv').removeClass('hide');
            $('#generateDiv #optionDiv').removeClass('hide');
            if(option>1){
                $('#generateDiv #optionSelectDiv').removeClass('hide');
                $('#generateDiv #durationDiv').addClass('hide');
            }
            if(jQuery.inArray('Y', emp_stats_g) != -1){
                $('#generateDiv #durationDiv').addClass('hide');
            }
            if(jQuery.inArray('N', emp_stats_g) != -1){
                $('#generateDiv #include_peraDiv').addClass('hide');
            }
        }
        if(payroll_type==2){
            $('#generateDiv #include_peraDiv').addClass('hide');
            $('#generateDiv #durationDiv').addClass('hide');
        }
    }else{        
        $('#generateDiv #unclaimedDiv').addClass('hide');
        $('#generateDiv #monthSingleDiv').removeClass('hide');
        $('#generateDiv #monthMultipleDiv').addClass('hide');
        $('#generateDiv #durationDiv').addClass('hide');
        $('#generateDiv #optionDiv').addClass('hide');
        $('#generateDiv #optionSelectDiv').addClass('hide');
        $('#generateDiv #include_peraDiv').addClass('hide');        
    }
}
function generate_list(){
    var thisBtn = $('#generateDiv button[name="submit"]');
    var fund_sources = [];
    var fund_services = [];
    var emp_stats = [];
    var months = [];
    var x = 0;
    var year = $('#generateDiv select[name="year"] option:selected').val();
    var month = $('#generateDiv select[name="month"] option:selected').val();
    var payroll_type = $('#generateDiv select[name="payroll_type"] option:selected').val();
    var duration = $('#generateDiv select[name="duration"] option:selected').val();
    var option = $('#generateDiv select[name="option"] option:selected').val();
    var day_from = $('#generateDiv input[name="day_from"]').val();
    var day_to = $('#generateDiv input[name="day_to"]').val();
    var status = $('#generateDiv select[name="status"] option:selected').val();
    var include_pera = $('#generateDiv select[name="include_pera"] option:selected').val();

    $('#generateDiv #fund_sourceDiv').removeClass('border-require');
    $('#generateDiv #fund_serviceDiv').removeClass('border-require');
    $('#generateDiv #emp_statDiv').removeClass('border-require');
    $('#generateDiv #monthsDiv').removeClass('border-require');
    $('#generateDiv input[name="day_from"]').removeClass('border-require');
    $('#generateDiv input[name="day_to"]').removeClass('border-require');

    $('#generateDiv select[name="emp_stat[]"] option:selected').each(function() {
        emp_stats.push($(this).val());
    });

    $('#generateDiv select[name="fund_source[]"] option:selected').each(function() {
        fund_sources.push($(this).val());
    });

    $('#generateDiv select[name="fund_service[]"] option:selected').each(function() {
        fund_services.push($(this).val());
    });
    
    $('#generateDiv select[name="months[]"] option:selected').each(function() {
        months.push($(this).val());      
    });
    
    if(fund_sources==''){
        $('#generateDiv #fund_sourceDiv').addClass('border-require');
        toastr.error('Please select Fund Source');
        x++;
    }
    if(emp_stats==''){
        $('#generateDiv #emp_statDiv').addClass('border-require');
        toastr.error('Please select Employment Status');
        x++;
    }
    if(jQuery.inArray('5', emp_stats) != -1){
        if(months==''){
            $('#generateDiv #monthsDiv').addClass('border-require');
            toastr.error('Please select Month');
            x++;
        }
    }
    if(payroll_type<=2){
        if(option>1){
            if(day_from=='' || day_from<=0){
                $('#generateDiv input[name="day_from"]').addClass('border-require');
                toastr.error('Please input day from');
                x++;
            }
            if(day_to=='' || day_to<=0){
                $('#generateDiv input[name="day_to"]').addClass('border-require');
                x++;
            }
            if(day_from>day_to){
                $('#generateDiv input[name="day_from"]').addClass('border-require');
                $('#generateDiv input[name="day_to"]').addClass('border-require');
                toastr.error('Day from must lower than Day to');
                x++;
            }
        }
    }
    if(x==0){
        var form_data = {
            payroll_type:payroll_type
        };
        $.ajax({
            url: base_url+'/hrims/payroll/generate/table',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            beforeSend: function() {
                $('#generateDiv #listTableDiv').attr('disabled','disabled'); 
            },
            success : function(data){
                $('#generateDiv #listTableDiv').removeAttr('disabled');
                if(data=='error'){
                    toastr.error('Error!');
                }else{
                    $('#generateDiv #listTableDiv').html(data);
                    var form_data = {
                        url_table:base_url+'/hrims/payroll/generate/list',
                        tid:'listTable',
                        year:year,
                        month:month,
                        months:months,
                        payroll_type:payroll_type,
                        emp_stats:emp_stats,
                        fund_sources:fund_sources,
                        fund_services:fund_services,
                        duration:duration,
                        option:option,
                        day_from:day_from,
                        day_to:day_to,
                        status:status,
                        include_pera:include_pera
                    };
            
                    loadTablewLoader(form_data,thisBtn);
                }
            },
            error: function (){
                toastr.error('Error!');
                $('#generateDiv #listTableDiv').removeAttr('disabled');
            }
        });

        
    }
}
function generate(thisBtn){
    var employees = [];
    var months = [];
    var unclaimeds = [];
    var emp_stats = [];
    var fund_sources = [];
    var fund_services = [];
    var generate_option = thisBtn.val();
    var x = 0;
    var year = $('#generateDiv select[name="year"] option:selected').val();
    var month = $('#generateDiv select[name="month"] option:selected').val();
    var payroll_type = $('#generateDiv select[name="payroll_type"] option:selected').val();
    var duration = $('#generateDiv select[name="duration"] option:selected').val();
    var option = $('#generateDiv select[name="option"] option:selected').val();
    var day_from = $('#generateDiv input[name="day_from"]').val();
    var day_to = $('#generateDiv input[name="day_to"]').val();
    var status = $('#generateDiv select[name="status"] option:selected').val();
    var include_pera = $('#generateDiv select[name="include_pera"] option:selected').val();
    var account_title = $('#generateDiv select[name="account_title"] option:selected').val();

    $('#generateDiv select[name="months[]"] option:selected').each(function() {
        months.push($(this).val());      
    });
    $('#generateDiv select[name="unclaimeds[]"] option:selected').each(function() {
        unclaimeds.push($(this).val());      
    });
    $('#generateDiv select[name="fund_source[]"] option:selected').each(function() {
        fund_sources.push($(this).val());      
    });
    $('#generateDiv select[name="fund_service[]"] option:selected').each(function() {
        fund_services.push($(this).val());
    });
    $('#generateDiv select[name="emp_stat[]"] option:selected').each(function() {
        emp_stats.push($(this).val());
    });
    $('#generateDiv .employee:checked').each(function() {
        employees.push($(this).val());      
    });
    if(employees==''){
        toastr.error('Please select an employee!');
        x++;
    }

    if(x==0){
        var form_data = {
            year:year,
            month:month,
            months:months,
            payroll_type:payroll_type,
            emp_stats:emp_stats,
            fund_sources:fund_sources,
            fund_services:fund_services,
            duration:duration,
            option:option,
            day_from:day_from,
            day_to:day_to,
            status:status,
            employees:employees,
            generate_option:generate_option,
            include_pera:include_pera,
            unclaimeds:unclaimeds,
            account_title:account_title
        };
        $.ajax({
            url: base_url+'/hrims/payroll/generate/generate',
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
                    generate_list();
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