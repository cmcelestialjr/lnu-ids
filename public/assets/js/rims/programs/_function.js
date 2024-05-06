
function curriculum_div(thisBtn){
    var id = $('#curriculumModal #curriculumDiv select[name="curriculum"] option:selected').val();
    var year_level = $('#curriculumModal #curriculumDiv select[name="year_level[]"] option:selected').toArray().map(item => item.value);
    var status_course = $('#curriculumModal #curriculumDiv select[name="status_course[]"] option:selected').toArray().map(item => item.value);
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/programs/curriculumInfo',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('input-loading');
            $('#curriculumModal #curriculumInfoDiv').addClass('disabled');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading');
            $('#curriculumModal #curriculumInfoDiv').removeClass('disabled');
            if(data=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');
            }else{
                toastr.success('Success');
                thisBtn.addClass('input-success');
                $('#curriculumModal #curriculumInfoDiv').html(data);
                $(".select2-default-info").select2({
                    dropdownParent: $("#curriculumInfoDiv")
                });
                var form_data = {
                    url_table:base_url+'/rims/programs/curriculumTable',
                    tid:'curriculumTable',
                    id:id,
                    level:year_level,
                    status:status_course
                };
                loadDivwLoader(form_data,thisBtn);
            }
            setTimeout(function() {
                thisBtn.removeClass('input-success');
                thisBtn.removeClass('input-error');
            }, 3000);
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
        }
    });

}
