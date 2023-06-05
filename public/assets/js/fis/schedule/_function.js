function schedule_table(){
    var thisBtn = $('#scheduleDiv #list select');
    var school_year = $('#scheduleDiv #list select[name="school_year"] option:selected').val();  
    var level = [];
    $('#scheduleDiv #list select[name="level[]"] option:selected').each(function () {
        level.push($(this).val());
    }); 
    var form_data = {
        url_table:base_url+'/fis/schedule/scheduleTable',
        tid:'scheduleTable',
        school_year:school_year,
        level:level        
    };
    loadDivwLoader(form_data,thisBtn);
}
function grade_level(){
    var thisBtn = $('#scheduleDiv #list select');
    var school_year = $('#scheduleDiv #list select[name="school_year"] option:selected').val();  
    var form_data = {
        school_year:school_year
    };
    $.ajax({
        url: base_url+'/fis/students/gradeLevel',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            if(data=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');                
            }else{
                thisBtn.addClass('input-success');
                $('#scheduleDiv #list #gradeLevelDiv').html(data);
                $(".select2-gradeLevel").select2({
                    dropdownParent: $("#gradeLevelDiv")
                });
                schedule_table();
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