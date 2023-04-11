function view_schedule(){
    var thisBtn = $('#scheduleDiv select[name="school_year"]');
    var id = thisBtn.val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/sections/programsSelect',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
            $('#scheduleDiv #programsSelectDiv select[name="program"]').attr('disabled','disabled');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            $('#scheduleDiv #programsSelectDiv select[name="program"]').removeAttr('disabled');
            if(data=='error'){
                toastr.success('Error');
                thisBtn.addClass('input-error');
            }else{
                thisBtn.addClass('input-success');                
                $('#scheduleDiv #programsSelectDiv').html(data);
                $(".select2-programsSelect").select2({
                    dropdownParent: $("#programsSelectDiv")
                });
                view_by_program();
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
function view_search_div(){
    var thisBtn = $('#scheduleDiv #search select');
    var school_year = $('#scheduleDiv #search select[name="school_year_search"] option:selected').val();
    var option = $('#scheduleDiv #search select[name="option"] option:selected').val();    
    var form_data = {
        option:option,
        school_year:school_year
    };
    $.ajax({
        url: base_url+'/rims/schedule/searchDiv',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
            $('#scheduleDiv #search #schedSearchDiv').addClass('opacity6');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            $('#scheduleDiv #search #schedSearchDiv').removeClass('opacity6');
            if(data=='error'){
                toastr.success('Error');
                thisBtn.addClass('input-error');
            }else{
                thisBtn.addClass('input-success');                
                $('#scheduleDiv #search #schedSearchDiv').html(data);                
                $(".select2-search").select2({
                    dropdownParent: $("#schedSearchDiv")
                });                
                courseSearch(school_year);
                sched_search_div();
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
function sched_search_div(){
    var thisBtn = $('#scheduleDiv #search select');
    var school_year = $('#scheduleDiv #search select[name="school_year_search"] option:selected').val();
    var option = $('#scheduleDiv #search select[name="option"] option:selected').val();
    var option_select = $('#scheduleDiv #search #schedSearchDiv select[name="option_select"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/schedule/searchTable',
        tid:'searchTable',
        school_year:school_year,
        option:option,
        option_select:option_select
    };
    loadTablewLoader(form_data,thisBtn);
}
function sched_wo_table(){
    var thisBtn = $('#scheduleDiv #wo select');
    var school_year = $('#scheduleDiv #wo select[name="school_year_wo"] option:selected').val();
    var option = [];
    $('#scheduleDiv #wo select[name="option_wo[]"] option:selected').each(function() {
        option.push($(this).val());
    });
    var form_data = {
        url_table:base_url+'/rims/schedule/schedWoTable',
        tid:'schedWoTable',
        school_year:school_year,
        option:option
    };
    loadTablewLoader(form_data,thisBtn);
}
function view_by_program(){
    var thisBtn = $('#scheduleDiv #view select');
    var school_year = $('#scheduleDiv #view select[name="school_year"] option:selected').val();
    var program = $('#scheduleDiv #view select[name="program"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/schedule/viewTable',
        tid:'viewTable',
        school_year:school_year,
        program:program
    };
    loadTablewLoader(form_data,thisBtn);
}