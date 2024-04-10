gradesDiv();


function gradesDiv(){
    $('#gradesDiv').html('<center>'+loadingTemplate()+'</center>');

    var selectOption = $('#gradesModal .select2-info');
    var id = $('#studentViewModal input[name="id"]').val();
    var program_level = $('#gradesModal select[name="program_level"] option:selected').val();

    var form_data = {
        id:id,
        program_level:program_level
    };
    $.ajax({
        url: base_url+'/rims/student/studentGradesList',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            selectOption.attr('disabled','disabled'); 
            $('#certificationModal #program_level').removeClass('border-require');
        },
        success : function(data){
            selectOption.removeAttr('disabled');
            $('#gradesDiv').html(data);            
        },
        error: function (){
            toastr.error('Error!');
            selectOption.removeAttr('disabled');
        }
    });
}