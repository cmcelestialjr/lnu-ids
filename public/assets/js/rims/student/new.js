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
$(document).off('click', '#studentCourseAddModal button[name="submit"]').on('click', '#studentCourseAddModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#studentTORModal input[name="id"]').val();
    var level = $('#studentTORModal select[name="level"] option:selected').val();
    var student_program = $('#studentCourseAddModal select[name="student_program"] option:selected').val();
    var in_list = $('#studentCourseAddModal input[name="in_list"]:checked').val();
    var from_school = $('#studentCourseAddModal input[name="from_school"]').val();
    var program_name = $('#studentCourseAddModal input[name="program_name"]').val();
    var program_shorten = $('#studentCourseAddModal input[name="program_shorten"]').val();
    var year_from = $('#studentCourseAddModal input[name="year_from"]').val();
    var period = $('#studentCourseAddModal select[name="period"] option:selected').val();
    var option = $('#studentCourseAddModal select[name="option"] option:selected').val();
    var x = 0;
    if(in_list=='Yes'){
        $('#studentCourseAddModal input[name="from_school"]').removeClass('border-require');
        if(from_school==''){
            toastr.error('Please input school name');
            $('#studentCourseAddModal input[name="from_school"]').addClass('border-require');
            x++;
        }
        $('#studentCourseAddModal input[name="program_name"]').removeClass('border-require');
        if(program_name==''){
            toastr.error('Please input Program name');
            $('#studentCourseAddModal input[name="program_name"]').addClass('border-require');
            x++;
        }
        $('#studentCourseAddModal input[name="program_shorten"]').removeClass('border-require');
        if(program_shorten==''){
            toastr.error('Please input Program shorten');
            $('#studentCourseAddModal input[name="program_shorten"]').addClass('border-require');
            x++;
        }
    }
    $('#studentCourseAddModal input[name="year_from"]').removeClass('border-require');
    if(year_from==''){
        toastr.error('Please input School year from');
        $('#studentCourseAddModal input[name="year_from"]').addClass('border-require');
        x++;
    }
    var course_codes = [];    
    $('#studentCourseAddModal .course_code').removeClass('border-require');
    $('#studentCourseAddModal .course_code').each(function(index, element) {
        var value = $(element).val();
        if(value==''){
            $(this).addClass('border-require');
            x++;
        }
        course_codes.push(value);
    });

    var course_descs = [];
    $('#studentCourseAddModal .course_desc').removeClass('border-require');
    $('#studentCourseAddModal .course_desc').each(function(index, element) {
        var value = $(element).val();
        if(value==''){
            $(this).addClass('border-require');
            x++;
        }
        course_descs.push(value);
    });

    var units = [];
    $('#studentCourseAddModal .unit').removeClass('border-require');
    $('#studentCourseAddModal .unit').each(function(index, element) {
        var value = $(element).val();
        if(value==''){
            $(this).addClass('border-require');
            x++;
        }
        units.push(value);
    });

    var labs = [];
    $('#studentCourseAddModal .lab').removeClass('border-require');
    $('#studentCourseAddModal .lab').each(function(index, element) {
        var value = $(element).val();
        if(value==''){
            $(this).addClass('border-require');
            x++;
        }
        labs.push(value);
    });

    var statuses = [];
    $('#studentCourseAddModal .statuses').removeClass('border-require');
    $('#studentCourseAddModal .statuses').each(function(index, element) {
        var value = $(element).val();
        if(value==''){
            $(this).addClass('border-require');
            x++;
        }
        statuses.push(value);
    });

    var ratings = [];
    var check_x = 1;
    $('#studentCourseAddModal .rating').removeClass('border-require');
    $('#studentCourseAddModal .rating').each(function(index, element) {
        var value = $(element).val();
        var option = $('#studentCourseAddModal #course_statuses'+check_x).find(':selected').data('option');
        if(option==1 && value==''){
            $(this).addClass('border-require');
            x++;
        }
        ratings.push(value);
        check_x++;
    });
    if(x==0){
        var form_data = {
            id:id,
            level:level,
            student_program:student_program,
            in_list:in_list,
            from_school:from_school,
            program_name:program_name,
            program_shorten:program_shorten,
            year_from:year_from,
            period:period,
            option:option,
            course_codes:course_codes,
            course_descs:course_descs,
            units:units,
            labs:labs,
            statuses:statuses,
            ratings:ratings,
        };
        $.ajax({
            url: base_url+'/rims/student/studentCourseAddModalSubmit',
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