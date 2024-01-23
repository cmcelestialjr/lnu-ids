$(document).off('click', '#educBg #new').on('click', '#educBg #new', function (e) {
    var thisBtn = $(this);
    educBgNew(thisBtn);
});
$(document).off('click', '#educBg .edit').on('click', '#educBg .edit', function (e) {
    var thisBtn = $(this);
    educBgEdit(thisBtn);
});
$(document).off('click', '#educBg .delete').on('click', '#educBg .delete', function (e) {
    var thisBtn = $(this);
    educBgDelete(thisBtn);
});
$(document).off('click', '#educBgNewSubmit').on('click', '#educBgNewSubmit', function (e) {
    var thisBtn = $(this);
    educBgNewSubmit(thisBtn);
});
$(document).off('click', '#educBgEditSubmit').on('click', '#educBgEditSubmit', function (e) {
    var thisBtn = $(this);
    educBgEditSubmit(thisBtn);
});
$(document).off('click', '#educBgDeleteSubmit').on('click', '#educBgDeleteSubmit', function (e) {
    var thisBtn = $(this);
    educBgDeleteSubmit(thisBtn);
});
$(document).off('click', '#educBgNewModal input[name="period_to_present"]').on('click', '#educBgNewModal input[name="period_to_present"]', function (e) {
    var thisBtn = $(this);
    $('#educBgNewModal input[name="period_to"]').prop('disabled', false);
    if(thisBtn.is(':checked')){
        $('#educBgNewModal input[name="period_to"]').prop('disabled', true);
    }
});
$(document).off('input', '#educBgNewModal input[name="name"]').on('input', '#educBgNewModal input[name="name"]', function (e) {
    var val = $(this).val();
    schoolSearch(val);
});
$(document).off('click', '#educBgNewModal #check_school').on('click', '#educBgNewModal #check_school', function (e) {
    var thisBtn = $(this);
    check_school(thisBtn);
});
$(document).off('change', '#educBgNewModal select[name="level"]').on('change', '#educBgNewModal select[name="level"]', function (e) {
    getPrograms();
});
$(document).off('change', '#educBgNewModal select[name="school"]').on('change', '#educBgNewModal select[name="school"]', function (e) {
    getPrograms();
});
$(document).off('click', '#educBgNewModal #check_program').on('click', '#educBgNewModal #check_program', function (e) {
    var thisBtn = $(this);
    check_program(thisBtn);
});
function educBgNew(thisBtn){
    var url = base_url+'/sims/information/educBgNew';
    var modal = 'info';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:''
    };
    loadModal(form_data,thisBtn);
}
function educBgEdit(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/sims/information/educBgEdit';
    var modal = 'info';
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
function educBgDelete(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/sims/information/educBgDelete';
    var modal = 'info';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function educBgNewSubmit(thisBtn){
    var level = $('#educBgNewModal select[name="level"] option:selected').val();   
    var school = $('#educBgNewModal select[name="school"] option:selected').val();
    var new_school = $('#educBgNewModal input[name="new_school"]').val();
    var check_school = 0;
    var p = $('#educBgNewModal select[name="level"] option:selected').data('p');
    var program = $('#educBgNewModal select[name="program"] option:selected').val();
    var new_program = $('#educBgNewModal input[name="new_program"]').val();
    var check_program = 0;
    var period_from = $('#educBgNewModal input[name="period_from"]').val();
    var period_to = $('#educBgNewModal input[name="period_to"]').val();
    var period_to_present = 0;
    var units_earned = $('#educBgNewModal input[name="units_earned"]').val();
    var year_grad = $('#educBgNewModal input[name="year_grad"]').val();
    var honors = $('#educBgNewModal input[name="honors"]').val();
    var x = 0;
    if($('#educBgNewModal input[name="period_to_present"]').is(':checked')){
        var period_to_present = 1;
    }

    $('#educBgNewModal #schoolSearch').removeClass('border-require');
    $('#educBgNewModal input[name="new_school"]').removeClass('border-require');
    $('#educBgNewModal #programSearch2').removeClass('border-require');
    $('#educBgNewModal input[name="new_program"]').removeClass('border-require');
    $('#educBgNewModal input[name="period_from"]').removeClass('border-require');
    $('#educBgNewModal input[name="period_to"]').removeClass('border-require');
    
    
    if (!isValidDate(period_from)) {
        toastr.error('Please input Period From');
        $('#educBgNewModal input[name="period_from"]').addClass('border-require');
        x++;
    }

    if(period_to_present==0){
        if (!isValidDate(period_to)) {
            toastr.error('Please input Period To');
            $('#educBgNewModal input[name="period_to"]').addClass('border-require');
            x++;
        }
    }

    if($('#educBgNewModal input[name="check_school"]').is(':checked')){
        var check_school = 1;
        if(new_school==''){
            toastr.error('Please input name of New School');
            $('#educBgNewModal input[name="new_school"]').addClass('border-require');
            x++;
        }
    }else{
        if(school==''){
            toastr.error('Please select School');
            $('#educBgNewModal #schoolSearch').addClass('border-require');
            x++;
        }
    }

    if(p=='w'){
        if($('#educBgNewModal input[name="check_program"]').is(':checked')){
            var check_program = 1;
            if(new_program==''){
                toastr.error('Please input name of Program');
                $('#educBgNewModal input[name="new_program"]').addClass('border-require');
                x++;
            }
        }else{
            if(program==''){
                toastr.error('Please select Program');
                $('#educBgNewModal #programSearch2').addClass('border-require');
                x++;
            }
        }
    }
    
    if(x==0){
        var form_data = {
            level:level,
            school:school,
            new_school:new_school,
            check_school:check_school,
            period_from:period_from,
            period_to:period_to,
            period_to_present:period_to_present,
            units_earned:units_earned,
            year_grad:year_grad,
            honors:honors,
            program:program,
            new_program:new_program,
            check_program:check_program
        };

        $.ajax({
            url: base_url+'/sims/information/educBgNewSubmit',
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
                        toastr.success('Success!');
                        $('#modal-info').modal('hide');
                        var id = 'educationalBgEdit';
                        informationEditDiv(id);
                        informationEducBg();
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
}

function educBgEditSubmit(thisBtn){
    var educ_id = $('#educBgNewModal input[name="educ_id"]').val();
    var level = $('#educBgNewModal select[name="level"] option:selected').val();   
    var school = $('#educBgNewModal select[name="school"] option:selected').val();
    var new_school = $('#educBgNewModal input[name="new_school"]').val();
    var check_school = 0;
    var p = $('#educBgNewModal select[name="level"] option:selected').data('p');
    var program = $('#educBgNewModal select[name="program"] option:selected').val();
    var new_program = $('#educBgNewModal input[name="new_program"]').val();
    var check_program = 0;
    var period_from = $('#educBgNewModal input[name="period_from"]').val();
    var period_to = $('#educBgNewModal input[name="period_to"]').val();
    var period_to_present = 0;
    var units_earned = $('#educBgNewModal input[name="units_earned"]').val();
    var year_grad = $('#educBgNewModal input[name="year_grad"]').val();
    var honors = $('#educBgNewModal input[name="honors"]').val();
    var x = 0;
    if($('#educBgNewModal input[name="period_to_present"]').is(':checked')){
        var period_to_present = 1;
    }

    $('#educBgNewModal #schoolSearch').removeClass('border-require');
    $('#educBgNewModal input[name="new_school"]').removeClass('border-require');
    $('#educBgNewModal #programSearch2').removeClass('border-require');
    $('#educBgNewModal input[name="new_program"]').removeClass('border-require');
    $('#educBgNewModal input[name="period_from"]').removeClass('border-require');
    $('#educBgNewModal input[name="period_to"]').removeClass('border-require');
    
    
    if (!isValidDate(period_from)) {
        toastr.error('Please input Period From');
        $('#educBgNewModal input[name="period_from"]').addClass('border-require');
        x++;
    }

    if(period_to_present==0){
        if (!isValidDate(period_to)) {
            toastr.error('Please input Period To');
            $('#educBgNewModal input[name="period_to"]').addClass('border-require');
            x++;
        }
    }

    if($('#educBgNewModal input[name="check_school"]').is(':checked')){
        var check_school = 1;
        if(new_school==''){
            toastr.error('Please input name of New School');
            $('#educBgNewModal input[name="new_school"]').addClass('border-require');
            x++;
        }
    }else{
        if(school==''){
            toastr.error('Please select School');
            $('#educBgNewModal #schoolSearch').addClass('border-require');
            x++;
        }
    }

    if(p=='w'){
        if($('#educBgNewModal input[name="check_program"]').is(':checked')){
            var check_program = 1;
            if(new_program==''){
                toastr.error('Please input name of Program');
                $('#educBgNewModal input[name="new_program"]').addClass('border-require');
                x++;
            }
        }else{
            if(program==''){
                toastr.error('Please select Program');
                $('#educBgNewModal #programSearch2').addClass('border-require');
                x++;
            }
        }
    }
    
    if(x==0){
        var form_data = {
            educ_id:educ_id,
            level:level,
            school:school,
            new_school:new_school,
            check_school:check_school,
            period_from:period_from,
            period_to:period_to,
            period_to_present:period_to_present,
            units_earned:units_earned,
            year_grad:year_grad,
            honors:honors,
            program:program,
            new_program:new_program,
            check_program:check_program
        };

        $.ajax({
            url: base_url+'/sims/information/educBgEditSubmit',
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
                        toastr.success('Success!');
                        $('#modal-info').modal('hide');
                        var id = 'educationalBgEdit';
                        informationEditDiv(id);
                        informationEducBg();
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
}
function educBgDeleteSubmit(thisBtn){
    var id = thisBtn.data('id');
    var form_data = {
        id:id
    };

    $.ajax({
        url: base_url+'/sims/information/educBgDeleteSubmit',
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
                    toastr.success('Success!');
                    $('#modal-info').modal('hide');
                    var id = 'educationalBgEdit';
                    informationEditDiv(id);
                    informationEducBg();
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
function schoolSearch(val){
    var datalist = $('#educBgNewModal #schoolOptions');
    var form_data = {
        val:val
    };
    $.ajax({
        url: base_url+'/search/school',
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
            datalist.empty();
            $.each(data, function(index, option) {
                datalist.append('<div class="autocomplete-option">'+ option +'</div>');
            });
        },
        error: function (){

        }
    });    
}
function isValidDate(dateString) {
    var dateString = dateString.replace(/\//g, "-");
    var pattern = /^(\d{2})-(\d{2})-(\d{4})$/;
    if (!pattern.test(dateString)) {
        return false;
    }

    var parts = dateString.split("-");
    
    var month = parseInt(parts[0], 10);
    var day = parseInt(parts[1], 10);
    var year = parseInt(parts[2], 10);

    var date = new Date(year, month - 1, day); // Month is 0-based
    
    return (
        date.getFullYear() === year &&
        date.getMonth() + 1 === month &&
        date.getDate() === day
    );
}
function check_school(thisBtn){
    $('#educBgNewModal #schoolSearch').removeClass('hide');
    $('#educBgNewModal #schoolNewDiv').addClass('hide');
    if(thisBtn.is(':checked')){
        $('#educBgNewModal #schoolNewDiv').removeClass('hide');
        $('#educBgNewModal #schoolSearch').addClass('hide');
    }
    getPrograms();
}
function check_program(thisBtn){
    $('#educBgNewModal #programSearch2').removeClass('hide');
    $('#educBgNewModal #programNewDiv').addClass('hide');
    if(thisBtn.is(':checked')){
        $('#educBgNewModal #programNewDiv').removeClass('hide');
        $('#educBgNewModal #programSearch2').addClass('hide');
    }    
}
function getPrograms(){
    var level = $('#educBgNewModal select[name="level"] option:selected').val();
    var p = $('#educBgNewModal select[name="level"] option:selected').data('p');
    var school = $('#educBgNewModal select[name="school"] option:selected').val();
    var x = 0;

    $('#educBgNewModal #programsDiv').addClass('hide'); 

    if(p!='w'){
        x++;
    }

    if($('#educBgNewModal input[name="check_school"]').is(':checked')){
        var school = 1;
    }

    if(school==''){
        x++;
    }
    if(x==0){
        $('#educBgNewModal #programsDiv').removeClass('hide'); 
        programSearch2(level,school);
    }
}
function informationEducBg(){
    var thisBtn = $('#informationEducBg');
    $.ajax({
        url: base_url+'/sims/information/informationEducBg',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        cache: false,
        beforeSend: function() {
            thisBtn.addClass('disabled'); 
        },
        success : function(data){
            thisBtn.removeClass('disabled'); 
            thisBtn.html(data);            
        },
        error: function (){
            thisBtn.removeClass('disabled');
        }
    });
}
