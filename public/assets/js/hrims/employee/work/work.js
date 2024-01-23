position_list('positionList');
designation_list('designationList');
$(document).off('click', '#workNewModal button[name="submit"]').on('click', '#workNewModal button[name="submit"]', function (e) {
    var thisBtn = $(this); 
    var div = 'workNewModal';
    var url = 'newSubmit';
    work_submit(thisBtn,div,url);   
});
$(document).off('change', '#workNewModal select[name="position_id"]').on('change', '#workNewModal select[name="position_id"]', function (e) {
    var thisBtn = $(this); 
    var div = 'workNewModal';
    position_shorten_get(thisBtn,div);   
});
$(document).off('click', '#workNewModal input[name="date_to_option"]').on('click', '#workNewModal input[name="date_to_option"]', function (e) {
    var thisBtn = $(this); 
    var div = 'workNewModal';
    date_to_option(thisBtn,div); 
});
$(document).off('change', '#workNewModal select[name="designation"]').on('change', '#workNewModal select[name="designation"]', function (e) {
    var thisBtn = $(this); 
    var div = 'workNewModal';
    designation(thisBtn,div)
});
$(document).off('click', '#workNewModal input[name="position_option"]').on('click', '#workNewModal input[name="position_option"]', function (e) {
    var thisBtn = $(this); 
    var div = 'workNewModal';
    position_option(thisBtn,div);
});
$(document).off('click', '#workEditModal button[name="submit"]').on('click', '#workEditModal button[name="submit"]', function (e) {
    var thisBtn = $(this); 
    var div = 'workEditModal';
    var url = 'editSubmit';
    work_submit(thisBtn,div,url);   
});
$(document).off('change', '#workEditModal select[name="position_id"]').on('change', '#workEditModal select[name="position_id"]', function (e) {
    var thisBtn = $(this); 
    var div = 'workEditModal';
    position_shorten_get(thisBtn,div);   
});
$(document).off('click', '#workEditModal input[name="date_to_option"]').on('click', '#workEditModal input[name="date_to_option"]', function (e) {
    var thisBtn = $(this); 
    var div = 'workEditModal';
    date_to_option(thisBtn,div); 
});
$(document).off('change', '#workEditModal select[name="designation"]').on('change', '#workEditModal select[name="designation"]', function (e) {
    var thisBtn = $(this); 
    var div = 'workEditModal';
    designation(thisBtn,div)
});
$(document).off('click', '#workEditModal input[name="position_option"]').on('click', '#workEditModal input[name="position_option"]', function (e) {
    var thisBtn = $(this); 
    var div = 'workEditModal';
    position_option(thisBtn,div);
});
function work_submit(thisBtn,div,url){
    var id = $('#'+div+' input[name="id"]').val();
    var date_from = $('#'+div+' input[name="date_from"]').val();
    var date_to_option = $('#'+div+' input[name="date_to_option"]:checked').val();
    var date_to = $('#'+div+' input[name="date_to"]').val();
    var position_option = $('#'+div+' input[name="position_option"]:checked').val();
    var position_id = $('#'+div+' select[name="position_id"] option:selected').val();
    var position_title = $('#'+div+' input[name="position_title"]').val();
    var position_shorten = $('#'+div+' input[name="position_shorten"]').val();
    var salary = $('#'+div+' input[name="salary"]').val();
    var sg = $('#'+div+' input[name="sg"]').val();
    var step = $('#'+div+' input[name="step"]').val();
    var gov_service = $('#'+div+' select[name="gov_service"] option:selected').val();
    var emp_stat = $('#'+div+' select[name="emp_stat"] option:selected').val();
    var fund_source = $('#'+div+' select[name="fund_source"] option:selected').val();
    var fund_services = $('#'+div+' select[name="fund_services"] option:selected').val();
    var designation = $('#'+div+' select[name="designation"] option:selected').val();
    var credit_type = $('#'+div+' select[name="credit_type"] option:selected').val();
    var role = $('#'+div+' select[name="role"] option:selected').val();
    var type = $('#'+div+' select[name="type"] option:selected').val();
    var office = $('#'+div+' input[name="office"]').val();
    var cause = $('#'+div+' input[name="cause"]').val();
    var separation = $('#'+div+' input[name="separation"]').val();
    var lwop = $('#'+div+' textarea[name="lwop"]').val();
    var remarks = $('#'+div+' textarea[name="remarks"]').val();
    var x = 0;
    $('#'+div+' input[name="date_from"]').removeClass('border-require');
    $('#'+div+' input[name="date_to"]').removeClass('border-require');
    $('#'+div+' input[name="position_title"]').removeClass('border-require');
    $('#'+div+' input[name="position_shorten"]').removeClass('border-require');
    $('#'+div+' input[name="salary"]').removeClass('border-require');
    $('#'+div+' input[name="sg"]').removeClass('border-require');
    $('#'+div+' input[name="step"]').removeClass('border-require');
    $('#'+div+' #positionList').removeClass('border-require');
    if(date_from==''){
        $('#'+div+' input[name="date_from"]').addClass('border-require');
        toastr.error('Please input Date from');
        x++;
    }
    if(date_to_option=='date' && date_to==''){
        $('#'+div+' input[name="date_to"]').addClass('border-require');
        toastr.error('Please input Date To');
        x++;
    }
    
    if(position_option=='None'){
        if(position_title==''){
            $('#'+div+' input[name="position_title"]').addClass('border-require');
            toastr.error('Please input Position Title');
            x++;
        }
        if(position_shorten==''){
            $('#'+div+' input[name="position_shorten"]').addClass('border-require');
            toastr.error('Please input Position Shorten');
            x++;
        }
        if(salary==''){
            $('#'+div+' input[name="salary"]').addClass('border-require');
            toastr.error('Please input Salary');
            x++;
        }
        if(sg==''){
            $('#'+div+' input[name="sg"]').addClass('border-require');
            toastr.error('Please input Salary');
            x++;
        }
        if(step==''){
            $('#'+div+' input[name="step"]').addClass('border-require');
            toastr.error('Please input Salary');
            x++;
        }
    }else{
        if(!position_id){
            $('#'+div+' #positionList').addClass('border-require');
            toastr.error('Please select Position');
            x++;
        }        
    }
    if(x==0){
        var form_data = {
            id:id,
            date_from:date_from,
            date_to_option:date_to_option,
            date_to:date_to,
            position_option:position_option,
            position_id:position_id,
            position_title:position_title,
            position_shorten:position_shorten,
            salary:salary,
            sg:sg,
            step:step,
            gov_service:gov_service,
            emp_stat:emp_stat,
            fund_source:fund_source,
            fund_services:fund_services,
            designation:designation,
            credit_type:credit_type,
            role:role,
            type:type,
            office:office,
            cause:cause,
            separation:separation,
            lwop:lwop,
            remarks:remarks,
        };
        $.ajax({
            url: base_url+'/hrims/employee/work/'+url,
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
                    if(url=='newSubmit'){
                        $('#modal-primary').modal('hide');
                    }else{
                        var id = data.id;
                    }
                    work_table(id);
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
}

function position_shorten_get(thisBtn,div){
    var id = thisBtn.val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/hrims/employee/work/positionShortenGet',
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
            $('#'+div+' input[name="position_title"]').val('');
            $('#'+div+' input[name="position_shorten"]').val('');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            if(data.result=='success'){                    
                toastr.success('Success');
                thisBtn.addClass('input-success');
                $('#'+div+' input[name="position_title"]').val(data.title);
                $('#'+div+' input[name="position_shorten"]').val(data.shorten);
                $('#'+div+' input[name="salary"]').val(data.salary);
                $('#'+div+' input[name="sg"]').val(data.sg);
                $('#'+div+' input[name="step"]').val(data.step);
                $('#'+div+' select[name="emp_stat"]').val(data.emp_stat).change();
                $('#'+div+' select[name="fund_source"]').val(data.fund_source).change();
                $('#'+div+' select[name="fund_services"]').val(data.fund_services).change();
                $('#'+div+' select[name="role"]').val(data.role).change();
                $('#'+div+' select[name="gov_service"]').val(data.gov_service).change();
                $('#'+div+' select[name="designation"]').empty();
                $('#'+div+' select[name="designation"]').append('<option value="'+data.designation+'">'+data.designation_name+'</option>');
                $('#'+div+' select[name="credit_type"]').attr('disabled', true);
                $('#'+div+' select[name="credit_type"]').val('none').change(); 
                if(data.designation!='none'){
                    $('#'+div+' select[name="credit_type"]').attr('disabled', false);
                }
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
function position_option(thisBtn,div){
    var val = thisBtn.val();
    $('#'+div+' #positionList').removeClass('hide');
    $('#'+div+' input[name="position_title"]').addClass('hide');
    $('#'+div+' input[name="position_shorten"]').attr('readonly', true);
    $('#'+div+' input[name="position_title"]').removeClass('border-require');
    $('#'+div+' input[name="position_shorten"]').removeClass('border-require');
    $('#'+div+' #positionList').removeClass('border-require');

    // $('#'+div+' input[name="salary"]').attr('readonly', true);
    $('#'+div+' input[name="salary"]').val('');

    // $('#'+div+' input[name="sg"]').attr('readonly', true);
    $('#'+div+' input[name="sg"]').val('');

    // $('#'+div+' input[name="step"]').attr('readonly', true);
    $('#'+div+' input[name="step"]').val('');

    $('#'+div+' select[name="emp_stat"]').attr('disabled', true);
    $('#'+div+' select[name="fund_source"]').attr('disabled', true);
    $('#'+div+' select[name="fund_services"]').attr('disabled', true);
    $('#'+div+' select[name="gov_service"]').attr('disabled', true);
    $('#'+div+' select[name="designation"]').attr('disabled', false);
    $('#'+div+' select[name="designation"]').empty();
    $('#'+div+' select[name="designation"]').append('<option value="none">None</option>');
    $('#'+div+' select[name="credit_type"]').attr('disabled', true);
    $('#'+div+' select[name="credit_type"]').val('none').change();
    $('#'+div+' select[name="role"]').attr('disabled', true);

    $('#'+div+' input[name="salary"]').removeClass('border-require');
    $('#'+div+' input[name="sg"]').removeClass('border-require');
    $('#'+div+' input[name="step"]').removeClass('border-require');

    if(val=='None'){
        $('#'+div+' #positionList').addClass('hide');
        $('#'+div+' input[name="position_title"]').removeClass('hide');
        $('#'+div+' input[name="position_shorten"]').attr('readonly', false);
        $('#'+div+' input[name="position_shorten"]').val('');
        $('#'+div+' input[name="position_title"]').val('');
        $('#'+div+' select[name="position_id"]').empty();

        // $('#'+div+' input[name="salary"]').attr('readonly', false);
        $('#'+div+' input[name="salary"]').val('');

        // $('#'+div+' input[name="sg"]').attr('readonly', false);
        $('#'+div+' input[name="sg"]').val('');

        // $('#'+div+' input[name="step"]').attr('readonly', false);
        $('#'+div+' input[name="step"]').val('');

        $('#'+div+' select[name="emp_stat"]').attr('disabled', false);
        $('#'+div+' select[name="fund_source"]').attr('disabled', false);
        $('#'+div+' select[name="fund_services"]').attr('disabled', false);
        $('#'+div+' select[name="gov_service"]').attr('disabled', false);
        $('#'+div+' select[name="role"]').attr('disabled', false);
        $('#'+div+' select[name="designation"]').attr('disabled', false);
        $('#'+div+' select[name="designation"]').empty();
        $('#'+div+' select[name="designation"]').append('<option value="none">None</option>');
    }
}
function designation(thisBtn,div){
    var val = thisBtn.val();
    $('#'+div+' select[name="credit_type"]').attr('disabled', false);
    if(val=='none'){
        $('#'+div+' select[name="credit_type"]').val('none').change();
        $('#'+div+' select[name="credit_type"]').attr('disabled', false);
    }
}
function date_to_option(thisBtn,div){
    var val = thisBtn.val();
    $('#'+div+' input[name="date_to"]').attr('readonly', true);
    if(val=='date'){
        $('#'+div+' input[name="date_to"]').attr('readonly', false);
    }
}
function work_table(id){
    var form_data = {
        url_table:base_url+'/hrims/employee/workTable',
        tid:'workTable',
        id:id
    };
    loadTable(form_data);
}