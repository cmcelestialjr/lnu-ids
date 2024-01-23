$(document).on('click', '#programsViewModal .coursesOpenModal', function (e) {
    var thisBtn = $(this);
    var id = $('#programsViewModal input[name="id"]').val();
    var url = base_url+'/rims/schoolYear/coursesOpenModal';
    var modal = 'primary';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/schoolYear/coursesListTable',
        tid:'coursesListTable',
        id:id,
        course_code:'',
        branch:1
    };
    loadModal(form_data,thisBtn);
});
$(document).off('change', '#coursesOpenModal select[name="course_code"]').on('change', '#coursesOpenModal select[name="course_code"]', function (e) {
    course_list_table();
});
$(document).off('click', '#coursesOpenModal button[name="submit_course"]').on('click', '#coursesOpenModal button[name="submit_course"]', function (e) {
    course_open();
});
function course_list_table(){
    var id = $('#coursesOpenModal input[name="id"]').val();
    var course_code = $('#coursesOpenModal select[name="course_code"] option:selected').val();
    var branch = $('#coursesOpenModal select[name="branch"] option:selected').val();
    var thisBtn = $('#coursesOpenModal select');
    var form_data = {
        url_table:base_url+'/rims/schoolYear/coursesListTable',
        tid:'coursesListTable',
        id:id,
        course_code:course_code,
        branch:branch
    };
    loadTablewLoader(form_data,thisBtn);
}
function course_open(){
    var thisBtn = $('#coursesOpenModal button[name="submit_course"]');
    var id = $('#coursesOpenModal input[name="id"]').val();
    var branch = $('#coursesOpenModal select[name="branch"] option:selected').val();
    var course_ids = [];
    $('#coursesOpenModal .courseCheck:checked').each(function() {
        course_ids.push($(this).data('id'));
    });
    if(course_ids.length<=0){
        toastr.error('Please select atleast 1 course');
    }else{
        var form_data = {
            id:id,
            course_ids:course_ids,
            branch:branch
        };
        $.ajax({
            url: base_url+'/rims/schoolYear/courseOpenSubmit',
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
                    course_list_table();
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
}