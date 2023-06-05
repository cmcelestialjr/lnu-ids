$(document).on('click', '#schoolYearDiv #new button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var year_from = $('#schoolYearDiv #new input[name="year_from"]').val();
    var year_to = $('#schoolYearDiv #new input[name="year_to"]').val();
    var grade_period = $('#schoolYearDiv #new select[name="grade_period"] option:selected').val();
    var date_duration = $('#schoolYearDiv #new input[name="date_duration"]').val();
    var date_extension = $('#schoolYearDiv #new input[name="date_extension"]').val();
    var enrollment_duration = $('#schoolYearDiv #new input[name="enrollment_duration"]').val();
    var enrollment_extension = $('#schoolYearDiv #new input[name="enrollment_extension"]').val();
    var add_dropping_duration = $('#schoolYearDiv #new input[name="add_dropping_duration"]').val();
    var add_dropping_extension = $('#schoolYearDiv #new input[name="add_dropping_extension"]').val();
    var x = 0;
    if(x==0){
        var form_data = {
            year_from:year_from,
            year_to:year_to,
            grade_period:grade_period,
            date_duration:date_duration,
            date_extension:date_extension,
            enrollment_duration:enrollment_duration,
            enrollment_extension:enrollment_extension,
            add_dropping_duration:add_dropping_duration,
            add_dropping_extension:add_dropping_extension
        };
        $.ajax({
            url: base_url+'/rims/schoolYear/new',
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
                    //view_programs(data.id,thisBtn);
                }else if(data.result=='exists'){
                    toastr.error('School Year and Semester Exists!');
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
});