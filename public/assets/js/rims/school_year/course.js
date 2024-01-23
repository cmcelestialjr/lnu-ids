$(document).on('change', '#coursesViewModal select[name="curriculum"]', function (e) {
    courses_view_modal();
});
$(document).on('change', '#coursesViewModal select[name="grade_level[]"]', function (e) {
    courses_view_modal();
});
$(document).on('click', '#coursesViewModal button[name="refresh"]', function (e) {
    courses_view_modal();
});
$(document).on('click', '#programsViewModal .coursesViewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/schoolYear/coursesViewModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/schoolYear/curriculumViewList',
        tid:'curriculumViewList',
        id:id,
        type:'modal'
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#coursesViewModal #curriculumViewList .courseStatusModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/schoolYear/courseViewStatusModal';
    var modal = 'info';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#courseViewStatusModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#courseViewStatusModal input[name="id"]').val();
    var status_id = $('#courseViewStatusModal select[name="status"] option:selected').val();
    var form_data = {
        id:id,
        status_id:status_id
    };
    $.ajax({
        url: base_url+'/rims/schoolYear/courseViewStatusSubmit',
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
                courses_view_modal();
                $('#modal-info').modal('hide');            
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
});
function courses_view_modal(){
    var thisBtn = $('#coursesViewModal select[name="curriculum"]');
    var curriculum_id = $('#coursesViewModal select[name="curriculum"] option:selected').val();
    var id = $('#coursesViewModal input[name="id"]').val();    
    var grade_level = $('#coursesViewModal select[name="grade_level[]"]').find('option:selected');
    var grade_levels = [];
    // Push each selected value into the array
    grade_level.each(function() {
        grade_levels.push($(this).val());
    });
    var form_data = {
        url_table:base_url+'/rims/schoolYear/curriculumViewList',
        tid:'curriculumViewList',
        id:id,
        curriculum_id:curriculum_id,
        grade_levels:grade_levels,
        type:'select'
    };
    loadDivwLoader(form_data,thisBtn);
}