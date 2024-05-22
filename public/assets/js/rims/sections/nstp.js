$(document).ready(function() {
    getNstpCount();
    $(document).off('click', '#nstp-tab')
    .on('click', '#nstp-tab', function (e) {
        nstpTable($(this));
    });
    $(document).off('click', '#nstp .nstpNewModal')
    .on('click', '#nstp .nstpNewModal', function (e) {
        nstpNewModal($(this));
    });
    $(document).off('change', '#nstpNewModal select[name="nstp"]')
    .on('change', '#nstpNewModal select[name="nstp"]', function (e) {
        getMaxStudent($(this));
    });
    $(document).off('click', '#nstpNewModal button[name="submit"]')
    .on('click', '#nstpNewModal button[name="submit"]', function (e) {
        nstpNewSubmit($(this));
    });
    $(document).off('click', '#nstp .studentList')
    .on('click', '#nstp .studentList', function (e) {
        studentList($(this));
    });
    $(document).off('click', '#nstp .editCount')
    .on('click', '#nstp .editCount', function (e) {
        editCount($(this));
    });
    $(document).off('click', '#nstpEditCountModal button[name="submit"]')
    .on('click', '#nstpEditCountModal button[name="submit"]', function (e) {
        editCountSubmit($(this));
    });
});
function nstpTable(){
    var thisBtn = $('#nstp .select2');
    var school_year_id = $('#nstp select[name="school_year"] option:selected').val();
    var branch_id = $('#nstp select[name="branch"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/nstp/nstpTable',
        tid:'nstpTable',
        school_year_id:school_year_id,
        branch_id:branch_id
    };
    loadTable(form_data,thisBtn);
}
function nstpNewModal(thisBtn){
    var url = base_url+'/rims/nstp/newModal';
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModal(form_data,thisBtn);
}
function getMaxStudent(){
    var max_student = $('#nstpNewModal select[name="nstp"] option:selected').data('n');
    $('#nstpNewModal input[name="max_student"]').val(max_student);
}
function nstpNewSubmit(thisBtn){
    var school_year_id = $('#nstp select[name="school_year"] option:selected').val();
    var branch_id = $('#nstp select[name="branch"] option:selected').val();
    var nstp_id = $('#nstpNewModal select[name="nstp"] option:selected').val();
    var max_student = $('#nstpNewModal input[name="max_student"]').val();
    var form_data = {
        school_year_id:school_year_id,
        branch_id:branch_id,
        nstp_id:nstp_id,
        max_student:max_student
    };
    $.ajax({
        url: base_url+'/rims/nstp/newSubmit',
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
            thisBtn.removeClass('input-loading');
            if(data.result=='success'){
                toastr.success('Success');
                thisBtn.addClass('input-success');
                $('#modal-default').modal('hide');
                nstpTable();
            }else{
                thisBtn.removeAttr('disabled');
                toastr.error(data.result);
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
function getNstpCount(){
    var school_year_id = $('#nstp select[name="school_year"] option:selected').val();
    var branch_id = $('#nstp select[name="branch"] option:selected').val();
    var form_data = {
        school_year_id:school_year_id,
        branch_id:branch_id
    };
    $.ajax({
        url: base_url+'/rims/nstp/getCount',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {

        },
        success : function(data){
            if(data.result=='success'){
                $('#nstp #cwts-section-count').html(data.cwts_section_count);
                $('#nstp #cwts-student-count').html(data.cwts_student_count);
                $('#nstp #lts-section-count').html(data.lts_section_count);
                $('#nstp #lts-student-count').html(data.lts_student_count);
                $('#nstp #rotc-section-count').html(data.rotc_section_count);
                $('#nstp #rotc-student-count').html(data.rotc_student_count);
            }
        },
        error: function (){
        }
    });
}
function studentList(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/rims/nstp/studentList';
    var modal = 'default';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/nstp/studentListTable',
        tid:'studentListTable',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function editCount(thisBtn){
    var id = thisBtn.data('id');
    var x = thisBtn.data('x');
    var url = base_url+'/rims/nstp/editCount';
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        x:x
    };
    loadModal(form_data,thisBtn);
}
function editCountSubmit(thisBtn){
    var id = thisBtn.data('id');
    var x = thisBtn.data('x');
    var idCheck = $('#nstp #editCount'+x).data('id');
    var max_student = $('#nstpEditCountModal input[name="max_student"]').val();
    var check_x = 0;

    $('#nstpEditCountModal input[name="max_student"]').removeClass('border-require');

    if(id!=idCheck){
        check_x++;
    }
    if(max_student<=0){
        $('#nstpEditCountModal input[name="max_student"]').addClass('border-require');
        check_x++;
    }

    if(check_x==0){
        var form_data = {
            id:id,
            max_student:max_student
        };
        $.ajax({
            url: base_url+'/rims/nstp/editCountSubmit',
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
                thisBtn.removeClass('input-loading');
                if(data.result=='success'){
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
                    $('#modal-default').modal('hide');
                    $('#nstp #editCount'+x).html(max_student);
                }else{
                    thisBtn.removeAttr('disabled');
                    toastr.error(data.result);
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
