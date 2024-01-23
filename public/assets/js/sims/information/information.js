selectOption();
$(document).off('change', '#information select[name="program_level"]').on('change', '#information select[name="program_level"]', function (e) {
    selectOption();
});
$(document).off('change', '#information select[name="option"]').on('change', '#information select[name="option"]', function (e) {
    selectOption();
});
$(document).off('change', '#selectOption select[name="school_year"]').on('change', '#selectOption select[name="school_year"]', function (e) {
    coursesList();
});
$(document).off('change', '#selectOption select').on('change', '#selectOption select', function (e) {
    curriculumList();
});
$(document).off('click', '.information_edit').on('click', '.information_edit', function (e) {
    var thisBtn = $(this);
    informationEdit(thisBtn);
});
$(document).off('click', '#proceedEdit').on('click', '#proceedEdit', function (e) {
    var thisBtn = $(this);
    proceedEdit(thisBtn);
});
$(document).off('click', '.informationEditDiv').on('click', '.informationEditDiv', function (e) {
    var id = $(this).data('id');
    informationEditDiv(id);
});
function selectOption(){
    var option = $('#information select[name="option"] option:selected').val();
    if(option==1){
        curriculumSelect();
    }else{
        coursesSelect();
    }
}
function coursesSelect(){
    var thisBtn = $('#information select');
    var program_level = $('#information select[name="program_level"] option:selected').val();
    var form_data = {
        id:program_level
    };
    $.ajax({
        url: base_url+'/sims/information/coursesSelect',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            $('#selectOption').html(data);
            $(".select2-courses").select2({
                dropdownParent: $("#selectOption")
            });
            coursesList();
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
}
function coursesList(){
    var thisBtn = $('#selectOption select[name="school_year"]');
    var school_year = $('#selectOption select[name="school_year"] option:selected').val();
    var form_data = {
        url_table:base_url+'/sims/information/coursesList',
        tid:'coursesList',
        id:school_year
    };
    loadDivwDisabled(form_data,thisBtn);
}
function curriculumSelect(){
    var thisBtn = $('#information select[name="program_level"]');
    var program_level = $('#information select[name="program_level"] option:selected').val();
    var form_data = {
        id:program_level
    };
    $.ajax({
        url: base_url+'/sims/information/curriculumSelect',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            $('#selectOption').html(data);
            $(".select2-curriculum").select2({
                dropdownParent: $("#selectOption")
            });
            curriculumList();
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
}
function curriculumList(){
    var thisBtn = $('#selectOption select');
    var program_level = $('#information select[name="program_level"] option:selected').val();
    var program = $('#selectOption select[name="programs"] option:selected').val();
    var curriculum = $('#selectOption select[name="curriculums"] option:selected').val();
    var year_level = [];
    $('#selectOption select[name="year_level[]"] option:selected').each(function(){
        year_level.push($(this).val());
    });
    var form_data = {
        url_table:base_url+'/sims/information/curriculumList',
        tid:'curriculumList',
        program_level:program_level,
        program:program,
        curriculum:curriculum,
        year_level:year_level
    };
    loadDivwDisabled(form_data,thisBtn);
}
function informationEdit(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/sims/information/informationEdit';
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function proceedEdit(thisBtn){
    var id = thisBtn.data('id');
    var password = $('#proceedPassword').val();

    $('#proceedPassword').removeClass('border-require');
    if(password==''){
        toastr.error('Password is required!');
        $('#proceedPassword').addClass('border-require');
    }else{
        var form_data = {
            id:id,
            password:password
        };
        $.ajax({
            url: base_url+'/sims/information/proceedEdit',
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
                        $('#modal-default').modal('hide');
                        informationEdiModal(id);
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
function informationEdiModal(id){
    var thisBtn = $('.information_edit');
    var url = base_url+'/sims/information/informationEdiModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'divDisabled',
        url_table:base_url+'/sims/information/informationEditDiv',
        tid:'informationEditDiv',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function informationEditDiv(id){
    var thisBtn = $('.information_edit');

    var form_data = {
        url_table:base_url+'/sims/information/informationEditDiv',
        tid:'informationEditDiv',
        id:id
    };
    loadDivwDisabled(form_data,thisBtn);
}