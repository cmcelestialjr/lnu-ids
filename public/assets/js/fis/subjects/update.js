$(document).on('click', '#studentGradeModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#studentGradeModal input[name="id"]').val();
    var sid = $('#studentGradeModal input[name="sid"]').val();
    var grade = $('#studentGradeModal input[name="grade"]').val();
    var final_grade = $('#studentGradeModal input[name="final_grade"]').val();
    var x = 0;
    // $('#studentGradeModal input[name="grade"]').removeClass('border-require');
    // $('#studentGradeModal input[name="final_grade"]').removeClass('border-require');
    // if(grade==0){
    //     $('#studentGradeModal input[name="grade"]').addClass('border-require');
    //     x++;   
    // }
    // if(final_grade==0){
    //     $('#studentGradeModal input[name="final_grade"]').addClass('border-require');
    //     x++;
    // }
    if(x==0){
        var form_data = {
            id:id,
            sid:sid,
            grade:grade,
            final_grade:final_grade
        };
        $.ajax({
            url: base_url+'/fis/subjects/studentGradeSubmit',
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
                    $('#modal-primary').modal('hide');
                    $('#studentsListModal #studentGrade'+sid).html(data.final_grade);
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