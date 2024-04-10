$(document).off('click', '#studentViewModal #selectProgram').on('click', '#studentViewModal #selectProgram', function (e) {
    var thisBtn = $(this);
    var id = $('#studentViewModal input[name="id"]').val();
    var url = base_url+'/rims/student/studentSelectProgramModal';
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
$(document).off('change', '#selectProgramModal select[name="program_level"]').on('change', '#selectProgramModal select[name="program_level"]', function (e) {
    var thisBtn = $(this);
    var selectOption = $('#selectProgramModal .select2-info');
    var program_level = thisBtn.val();
    var form_data = {
        program_level:program_level
    };
    $.ajax({
        url: base_url+'/rims/student/selectProgramList',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            selectOption.attr('disabled','disabled'); 
            $('#selectProgramModal #program_level').removeClass('border-require');
        },
        success : function(data){
            selectOption.removeAttr('disabled');
            if(data.result=='success'){
                $('#selectProgramModal select[name="program"]').empty();
                $('#selectProgramModal select[name="curriculum"]').empty();
                $('#selectProgramModal select[name="program"]').append('<option value="">Please Select Program</option>');                
                $('#selectProgramModal select[name="curriculum"]').append('<option value="">Please Select Curriculum</option>');
                $.each(data.list, function(index, item) {
                    $('#selectProgramModal select[name="program"]').append('<option value="' + item.id + '">' + item.shorten + ' - ' + item.name + '</option>');
                });
            }else{
                toastr.error(data.result);
            }
            
        },
        error: function (){
            toastr.error('Error!');
            selectOption.removeAttr('disabled');
        }
    });
});
$(document).off('change', '#selectProgramModal select[name="program"]').on('change', '#selectProgramModal select[name="program"]', function (e) {
    var thisBtn = $(this);
    var selectOption = $('#selectProgramModal .select2-info');
    var program = thisBtn.val();
    var form_data = {
        program:program
    };
    $.ajax({
        url: base_url+'/rims/student/selectCurriculumList',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            selectOption.attr('disabled','disabled'); 
            $('#selectProgramModal #program').removeClass('border-require');
        },
        success : function(data){
            selectOption.removeAttr('disabled');
            if(data.result=='success'){
                $('#selectProgramModal select[name="curriculum"]').empty();
                $('#selectProgramModal select[name="curriculum"]').append('<option value="">Please Select Curriculum</option>');
                $.each(data.list, function(index, item) {
                    $('#selectProgramModal select[name="curriculum"]').append('<option value="' + item.id + '">' + item.year_from + '-' + item.year_to + '</option>');
                });
            }else{
                toastr.error(data.result);
            }
            
        },
        error: function (){
            toastr.error('Error!');
            selectOption.removeAttr('disabled');
        }
    });
});
$(document).off('change', '#selectProgramModal select[name="curriculum"]').on('change', '#selectProgramModal select[name="curriculum"]', function (e) {
    $('#selectProgramModal #curriculum').removeClass('border-require');
});
$(document).off('blur', '#selectProgramModal input[name="year_from"]').on('blur', '#selectProgramModal input[name="year_from"]', function (e) {
    $('#selectProgramModal #year_from').removeClass('border-require');
});
$(document).off('change', '#selectProgramModal select[name="student_status"]').on('change', '#selectProgramModal select[name="student_status"]', function (e) {
    $('#selectProgramModal #student_status').removeClass('border-require');
});
$(document).off('click', '#selectProgramModal #selectProgramSubmit').on('click', '#selectProgramModal #selectProgramSubmit', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var branch = $('#selectProgramModal select[name="branch"] option:selected').val();
    var program_level = $('#selectProgramModal select[name="program_level"] option:selected').val();
    var program = $('#selectProgramModal select[name="program"] option:selected').val();
    var curriculum = $('#selectProgramModal select[name="curriculum"] option:selected').val();
    var year_from = $('#selectProgramModal input[name="year_from"]').val();
    var student_status = $('#selectProgramModal select[name="student_status"] option:selected').val();
    
    var x = 0;
    if ($('#selectProgramModal input[name="adopt_same_course"]').prop('checked')) {
        var adopt_same_course = 1;
    } else {
        var adopt_same_course = 0;
    }

    $('#selectProgramModal #program_level').removeClass('border-require');
    $('#selectProgramModal #program').removeClass('border-require');
    $('#selectProgramModal #curriculum').removeClass('border-require');
    $('#selectProgramModal #year_from').removeClass('border-require');
    $('#selectProgramModal #student_status').removeClass('border-require');
    if (!$.isNumeric(id)) {
        toastr.error('Error');
        x++;
    }
    if (!$.isNumeric(branch)) {
        $('#selectProgramModal #branch').addClass('border-require');
        toastr.error('Please select Branch!');
        x++;
    }
    if (!$.isNumeric(program_level)) {
        $('#selectProgramModal #program_level').addClass('border-require');
        toastr.error('Please select Program Level!');
        x++;
    }
    if (!$.isNumeric(program)) {
        $('#selectProgramModal #program').addClass('border-require');
        toastr.error('Please select Program!');
        x++;
    }
    if (!$.isNumeric(curriculum)) {
        $('#selectProgramModal #curriculum').addClass('border-require');
        toastr.error('Please select Curriculum!');
        x++;
    }
    if (!$.isNumeric(year_from) || year_from.length != 4) {
        $('#selectProgramModal #year_from').addClass('border-require');
        toastr.error('Please input valid year!');
        x++;
    }
    if (!$.isNumeric(student_status)) {
        $('#selectProgramModal #student_status').addClass('border-require');
        toastr.error('Please select Curriculum!');
        x++;
    }


    if(x==0){
        var form_data = {
            id:id,
            branch:branch,
            program_level:program_level,
            program:program,
            curriculum:curriculum,
            year_from:year_from,
            student_status:student_status,
            adopt_same_course:adopt_same_course,
        };
        $.ajax({
            url: base_url+'/rims/student/selectProgramSubmit',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
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
                    student_view(id,thisBtn);
                    $('#modal-info').modal('hide');
                }else{
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
});