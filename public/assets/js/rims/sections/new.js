$(document).on('click', '#sectionNewModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var curriculum = $('#sectionNewModal select[name="curriculum"] option:selected').val();
    var grade_level = $('#sectionNewModal #gradeLevelDiv select[name="grade_level"] option:selected').val();
    var no = $('#sectionNewModal input[name="no"]').val();
    if(no=='' || no <= 0){
        toastr.error('Please Input No. of Section');
        $('#sectionNewModal input[name="no"]').addClass('border-require');
    }else{
    var form_data = {
        curriculum:curriculum,
        grade_level:grade_level,
        no:no
    };
        $.ajax({
            url: base_url+'/rims/sections/sectionNewSubmit',
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
                    $('#modal-default').modal('hide');
                    view_sections_by_program();
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