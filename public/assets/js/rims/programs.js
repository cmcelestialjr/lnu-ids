view_programs();
$(document).on('change', '#programsDiv select[name="status"]', function (e) {
    view_programs();
});
$(document).on('click', '#curriculumDiv #curriculumTable .courseStatus', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/programs/courseStatus',
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
            if(data=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');
            }else{
                toastr.success('Success');
                thisBtn.addClass('input-success');

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
});
$(document).on('click', '#curriculumModal #curriculumDiv button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#curriculumModal #curriculumDiv select[name="curriculum"] option:selected').val();
    var year_level = $('#curriculumModal #curriculumDiv select[name="year_level[]"] option:selected').toArray().map(item => item.value);
    var status_course = $('#curriculumModal #curriculumDiv select[name="status_course[]"] option:selected').toArray().map(item => item.value);
    var form_data = {
        url_table:base_url+'/rims/programs/curriculumTable',
        tid:'curriculumTable',
        id:id,
        level:year_level,
        status:status_course
    };
    loadDivwLoader(form_data,thisBtn);
});
$(document).on('click', '#programsDiv .viewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/viewModal';
    var modal = 'default';
    var modal_size = 'modal-xxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/programs/curriculumTable',
        tid:'curriculumTable',
        id:id,
        level:'All',
        status:'All'
    };
    loadModal(form_data,thisBtn);
});
function view_programs(){
    var thisBtn = $('#programsDiv select[name="status"]');
    var status_id = thisBtn.val();
    var form_data = {
        url_table:base_url+'/rims/programs/viewTable',
        tid:'viewTable',
        status_id:status_id
    };
    loadTablewLoader(form_data,thisBtn);
}