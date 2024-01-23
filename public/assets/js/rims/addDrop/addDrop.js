$(document).off('change', '#advisementDiv select[name="optionAddDrop"]').on('change', '#advisementDiv select[name="optionAddDrop"]', function (e) {
    var val = $(this).val();
    var id = $('#advisementDiv select[name="student"] option:selected').val();
    if(id!=''){
        if(val=='Drop'){
            courses_table();
        }else{
            student_info();
        }
    }
});
$(document).off('click', '#advisementDiv #addDropDrop input[name="all"]').on('click', '#advisementDiv #addDropDrop input[name="all"]', function (e) {
    var thisBtn = $(this);
    if (thisBtn.is(':checked')) {
        $('#advisementDiv #addDropDrop .coursesList').prop('checked', true);
    } else {
        $('#advisementDiv #addDropDrop .coursesList').prop('checked', false);
    }
});
$(document).off('click', '#advisementDiv #addDropDrop button[name="drop_submit"]').on('click', '#advisementDiv #addDropDrop button[name="drop_submit"]', function (e) {
    var thisBtn = $(this);
    dropSubmit(thisBtn);
});
$(document).off('click', '#advisementDiv #addDropAdd button[name="add_submit"]').on('click', '#advisementDiv #addDropAdd button[name="add_submit"]', function (e) {
    var thisBtn = $(this);
    addSubmit(thisBtn);
});

function dropSubmit(thisBtn){
    var school_year = $('#advisementDiv select[name="school_year"] option:selected').val();
    var student_id = $('#advisementDiv select[name="student"] option:selected').val();
    var courses = [];
    $('#advisementDiv #addDropDrop .coursesList:checked').each(function () {
        courses.push($(this).val());
    });
    if(courses==''){
        toastr.error('Please select a course!');
    }else{
        var form_data = {
            school_year:school_year,
            student_id:student_id,
            courses:courses
        };
        $.ajax({
            url: base_url+'/rims/addDrop/dropSubmit',
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
                    thisBtn.addClass('input-success');
                    toastr.success('Success');
                    courses_table();
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

function addSubmit(thisBtn){
    var school_year = $('#advisementDiv select[name="school_year"] option:selected').val();
    var student_id = $('#advisementDiv select[name="student"] option:selected').val();
    var courses = [];
    var cid = [];
    $('#advisementDiv #addDropAdd .courseCheck:checked').each(function () {
        courses.push($(this).data('id'));
        cid.push($(this).data('cid'));
    });
    if(courses==''){
        toastr.error('Please select a course!');
    }else{
        var form_data = {
            school_year:school_year,
            student_id:student_id,
            courses:courses,
            cid:cid
        };
        $.ajax({
            url: base_url+'/rims/addDrop/addSubmit',
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
                    thisBtn.addClass('input-success');
                    toastr.success('Success');
                    student_info();
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
