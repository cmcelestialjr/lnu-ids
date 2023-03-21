view_sections();
$(document).on('change', '#sectionDiv select[name="school_year"]', function (e) {
    view_sections();
});
$(document).on('change', '#sectionDiv #programsSelectDiv select[name="program"]', function (e) {
    view_sections_by_program();
});
$(document).on('click', '#sectionDiv .sectionNewModal', function (e) {
    var thisBtn = $(this);
    var program_id = $('#sectionDiv #programsSelectDiv select[name="program"]').val();
    var id = $('#sectionDiv select[name="school_year"]').val();
    var url = base_url+'/rims/sections/sectionNewModal';
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        program_id:program_id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#sectionNewModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var curriculum = $('#sectionNewModal select[name="curriculum"] option:selected').val();
    var grade_level = $('#sectionNewModal select[name="grade_level"] option:selected').val();
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
$(document).on('change', '#sectionNewModal select[name="curriculum"]', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/sections/gradeLevelSelect',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
            $('#sectionNewModal select[name="grade_level"]').attr('disabled','disabled'); 
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            $('#sectionNewModal select[name="grade_level"]').removeAttr('disabled');
            if(data=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');                
            }else{
                toastr.success('Success');
                $('#sectionNewModal #gradeLevelDiv').html(data);
                $(".select2-gradeLevelSelect").select2({
                    dropdownParent: $("#sectionNewModal #gradeLevelDiv")
                });
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
function view_sections_by_program(){
    var thisBtn = $('#sectionDiv #programsSelectDiv select[name="program"]');
    var program_id = thisBtn.val();
    var id = $('#sectionDiv select[name="school_year"]').val();
    var form_data = {
        url_table:base_url+'/rims/sections/viewTable',
        tid:'viewTable',
        id:id,
        program_id:program_id
    };
    loadTablewLoader(form_data,thisBtn);
}
function view_sections(){
    var thisBtn = $('#sectionDiv select[name="school_year"]');
    var id = thisBtn.val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/sections/programsSelect',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
            $('#sectionDiv #programsSelectDiv select[name="program"]').attr('disabled','disabled');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            $('#sectionDiv #programsSelectDiv select[name="program"]').removeAttr('disabled');
            if(data=='error'){
                toastr.success('Error');
                thisBtn.addClass('input-error');
            }else{
                thisBtn.addClass('input-success');                
                $('#sectionDiv #programsSelectDiv').html(data);
                var program_id = $('#sectionDiv #programsSelectDiv select[name="program"]').val();
                $(".select2-programsSelect").select2({
                    dropdownParent: $("#programsSelectDiv")
                });
                var form_data = {
                    url_table:base_url+'/rims/sections/viewTable',
                    tid:'viewTable',
                    id:id,
                    program_id:program_id
                };
                loadTablewLoader(form_data,thisBtn);
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