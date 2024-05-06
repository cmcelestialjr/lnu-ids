programs();
schoolEducLoad();

$(document).off('change', '#editInfo select[name="department"]')
    .on('change', '#editInfo select[name="department"]', function (e) {
    programs();
});
$(document).off('change', '#editInfo select[name="program"]')
    .on('change', '#editInfo select[name="program"]', function (e) {
    curriculums();
});
$(document).off('click', '#editInfo input[name="school_not_list_check_new"]')
    .on('click', '#editInfo input[name="school_not_list_check_new"]', function (e) {
    schoolNotList($(this),'new','');
});
$(document).off('click', '#editInfo input[name="period_to_present_new"]')
    .on('click', '#editInfo input[name="period_to_present_new"]', function (e) {
        periodToPresent($(this),'new','');
});
$(document).off('click', '#editInfo input[name="program_not_list_check_new"]')
    .on('click', '#editInfo input[name="program_not_list_check_new"]', function (e) {
    programNotList($(this),'new','');
});
$(document).off('change', '#editInfo select[name="school_new"] ,#editInfo select[name="level_new"]')
    .on('change', '#editInfo select[name="school_new"], #editInfo select[name="level_new"]', function (e) {
    var level = $('#editInfo select[name="level_new"] option:selected').val();
    var school = $('#editInfo select[name="school_new"] option:selected').val();
    programSearch2(level,school);
});
$(document).off('change', '#editInfo select[name="level_new"]')
    .on('change', '#editInfo select[name="level_new"]', function (e) {
    var level_val = $('#editInfo select[name="level_new"] option:selected').data('val');
    programDisplay(level_val,'new','');
});
$(document).off('change', '#editInfo #edit-info input[name="religion_not_list_check"]')
    .on('change', '#editInfo #edit-info input[name="religion_not_list_check"]', function (e) {
    var thisBtn = $(this);
    $('#editInfo #edit-info input[name="religion_not_list"]').addClass('hide');
    if(thisBtn.is(':checked')){
        $('#editInfo #edit-info input[name="religion_not_list"]').removeClass('hide');
    }
});
$(document).off('click', '#editInfo .educ-update.show.active input[name="school_not_list_check_update"]')
    .on('click', '#editInfo .educ-update.show.active input[name="school_not_list_check_update"]', function (e) {
    schoolNotList($(this),'update','.educ-update.show.active');
});
$(document).off('click', '#editInfo .educ-update.show.active input[name="period_to_present_update"]')
    .on('click', '#editInfo .educ-update.show.active input[name="period_to_present_update"]', function (e) {
        periodToPresent($(this),'update','.educ-update.show.active');
});
$(document).off('click', '#editInfo .educ-update.show.active input[name="program_not_list_check_update"]')
    .on('click', '#editInfo .educ-update.show.active input[name="program_not_list_check_update"]', function (e) {
    programNotList($(this),'update','.educ-update.show.active');
});
$(document).off('change', '#editInfo .educ-update.show.active select[name="school_update"] ,#editInfo .educ-update.show.active select[name="level_update"]')
    .on('change', '#editInfo .educ-update.show.active select[name="school_update"], #editInfo .educ-update.show.active select[name="level_update"]', function (e) {
    var x = $('#editInfo .tab-update.active').data('x');
    var level = $('#editInfo #educ-update'+x+' select[name="level_update"] option:selected').val();
    var school = $('#editInfo #educ-update'+x+' select[name="school_update"] option:selected').val();
    programSearch4(level,school,x);
});
$(document).off('change', '#editInfo .educ-update.show.active select[name="level_update"]')
    .on('change', '#editInfo .educ-update.show.active select[name="level_update"]', function (e) {
    var level_val = $('#editInfo .educ-update.show.active select[name="level_update"] option:selected').data('val');
    programDisplay(level_val,'update','.educ-update.show.active');
});
$(document).off('click', '#editInfo .nav-link.tab-update')
        .on('click', '#editInfo .nav-link.tab-update', function (e) {
    var x = $(this).data('x');
    var level = $('#editInfo #educ-update'+x+' select[name="level_update"] option:selected').val();
    var school = $('#editInfo #educ-update'+x+' select[name="school_update"] option:selected').val();
    schoolSearch1(x);
    programSearch4(level,school,x);
});
$(document).off('click', '#editInfo #submitInfo')
    .on('click', '#editInfo #submitInfo', function (e) {
    var thisBtn = $(this);
    var active_tab = $('#editInfo .fam-tab.show.active').data('val');
    var array = ['Info','Contact','Educ','Fam'];
    if ($.inArray(active_tab, array) !== -1) {
        if(active_tab=='Info'){
            dataUpdateInfo(thisBtn);
        }else if(active_tab=='Contact'){
            dataUpdateContact(thisBtn);
        }else if(active_tab=='Educ'){
            dataUpdateEduc(thisBtn);
        }else if(active_tab=='Fam'){
            dataUpdateFam(thisBtn);
        }
    }
});
function schoolEducLoad(){
    var x = $('#editInfo .educ-update.show.active').data('x');
    var level = $('#editInfo #educ-update'+x+' select[name="level_update"] option:selected').val();
    var school = $('#editInfo #educ-update'+x+' select[name="school_update"] option:selected').val();
    schoolSearch1(x);
    programSearch4(level,school,x);
}
function schoolNotList(thisBtn,option,cl){
    $('#editInfo '+cl+' input[name="school_not_list_'+option+'"]').addClass('hide');
    $('#editInfo '+cl+' input[name="school_shorten_not_list_'+option+'"]').addClass('hide');
    $('#editInfo '+cl+' .school_div_'+option).removeClass('hide');
    if(thisBtn.is(':checked')){
        $('#editInfo '+cl+' input[name="school_not_list_'+option+'"]').removeClass('hide');
        $('#editInfo '+cl+' input[name="school_shorten_not_list_'+option+'"]').removeClass('hide');
        $('#editInfo '+cl+' .school_div_'+option).addClass('hide');
    }
}
function periodToPresent(thisBtn,option,cl){
    $('#editInfo '+cl+' input[name="period_to_'+option+'"]').prop('readonly', false);
    if(thisBtn.is(':checked')){
        $('#editInfo '+cl+' input[name="period_to_'+option+'"]').prop('readonly', true);
    }
}
function programNotList(thisBtn,option,cl){
    $('#editInfo '+cl+' input[name="program_not_list_'+option+'"]').addClass('hide');
    $('#editInfo '+cl+' .program_div_'+option).removeClass('hide');
    if(thisBtn.is(':checked')){
        $('#editInfo '+cl+' input[name="program_not_list_'+option+'"]').removeClass('hide');
        $('#editInfo '+cl+' .program_div_'+option).addClass('hide');
    }
}
function programDisplay(level_val,val,cl){
    $('#editInfo '+cl+' #program_tr_'+val).addClass('hide');
    if(level_val=='w'){
        $('#editInfo '+cl+' #program_tr_'+val).removeClass('hide');
    }
}
function programs(){
    var id = $('#studentViewModal input[name="id"]').val();
    var department = $('#editInfo select[name="department"] option:selected').val();
    var thisBtn = $('#editInfo .select-info');

    var form_data = {
        id:id,
        department:department
    };
    $.ajax({
        url: base_url+'/rims/student/studentProgramList',
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

            if(data.result=='success'){
                $('#editInfo select[name="program"]').empty();
                $('#editInfo select[name="curriculum"]').empty();
                $('#editInfo select[name="grade_level"]').empty();

                $.each(data.programs, function(index, item) {
                    if(data.program_id==item.id){
                        $('#editInfo select[name="program"]').append('<option value="' + item.id + '" selected>' + item.shorten + ' - ' + item.name + '</option>');
                    }else{
                        $('#editInfo select[name="program"]').append('<option value="' + item.id + '">' + item.shorten + ' - ' + item.name + '</option>');
                    }
                });
                $.each(data.grade_levels, function(index, item) {
                    if(data.grade_level_id==item.id){
                        $('#editInfo select[name="grade_level"]').append('<option value="' + item.id + '" selected>' + item.name + '</option>');
                    }else{
                        $('#editInfo select[name="grade_level"]').append('<option value="' + item.id + '">' + item.name + '</option>');
                    }
                });
                curriculums();
            }else{
                toastr.error(data.result);
            }
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
}
function curriculums(){
    var id = $('#studentViewModal input[name="id"]').val();
    var department = $('#editInfo select[name="department"] option:selected').val();
    var program = $('#editInfo select[name="program"] option:selected').val();
    var thisBtn = $('#editInfo .select-info');

    var form_data = {
        id:id,
        department:department,
        program:program
    };
    $.ajax({
        url: base_url+'/rims/student/studentCurriculumList',
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
                $('#editInfo select[name="curriculum"]').empty();
                $.each(data.curriculums, function(index, item) {
                    if(data.curriculum_id==item.id){
                        $('#editInfo select[name="curriculum"]').append('<option value="' + item.id + '" selected>' + item.year_from + '</option>');
                    }else{
                        $('#editInfo select[name="curriculum"]').append('<option value="' + item.id + '">' + item.year_from + '</option>');
                    }
                });
            }else{
                toastr.error(data.result);
            }
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
}
function dataUpdateInfo(thisBtn){
    var id = $('#studentViewModal input[name="id"]').val();
    var lastname = $('#editInfo #edit-info input[name="lastname"]').val();
    var firstname = $('#editInfo #edit-info input[name="firstname"]').val();
    var middlename = $('#editInfo #edit-info input[name="middlename"]').val();
    var extname = $('#editInfo #edit-info input[name="extname"]').val();
    var nickname = $('#editInfo #edit-info input[name="nickname"]').val();
    var sex = $('#editInfo #edit-info select[name="sex"] option:selected').val();
    var civil_status = $('#editInfo #edit-info select[name="civil_status"] option:selected').val();
    var dob = $('#editInfo #edit-info input[name="dob"]').val();
    var birthplace = $('#editInfo #edit-info input[name="birthplace"]').val();
    var country = $('#editInfo #edit-info select[name="country"] option:selected').val();
    var citizenship = $('#editInfo #edit-info input[name="citizenship"]').val();
    var religion = $('#editInfo #edit-info select[name="religion"] option:selected').val();
    var religion_not_list_check = $('#editInfo #edit-info input[name="religion_not_list_check"]');
    var religion_not_list = $('#editInfo #edit-info input[name="religion_not_list"]').val();
    var nstp_serial_no = $('#editInfo #edit-info input[name="nstp_serial_no"]').val();
    var branch = $('#editInfo #edit-info select[name="branch"] option:selected').val();
    var department = $('#editInfo #edit-info select[name="department"] option:selected').val();
    var program = $('#editInfo #edit-info select[name="program"] option:selected').val();
    var curriculum = $('#editInfo #edit-info select[name="curriculum"] option:selected').val();
    var grade_level = $('#editInfo #edit-info select[name="grade_level"] option:selected').val();
    var student_status = $('#editInfo #edit-info select[name="student_status"] option:selected').val();
    var x = 0;

    $('#editInfo #edit-info input[name="lastname"]').removeClass('border-require');
    $('#editInfo #edit-info input[name="firstname"]').removeClass('border-require');
    $('#editInfo #edit-info input[name="middlename"]').removeClass('border-require');
    $('#editInfo #edit-info input[name="nickname"]').removeClass('border-require');
    $('#editInfo #edit-info input[name="religion_not_list"]').removeClass('border-require');

    if(lastname=='' || lastname.length<2){
        $('#editInfo #edit-info input[name="lastname"]').addClass('border-require');
        x++;
    }
    if(firstname=='' || firstname.length<2){
        $('#editInfo #edit-info input[name="firstname"]').addClass('border-require');
        x++;
    }
    if(middlename!='' && middlename.length<2){
        $('#editInfo #edit-info input[name="middlename"]').addClass('border-require');
        x++;
    }
    if(nickname!='' && nickname.length<2){
        $('#editInfo #edit-info input[name="nickname"]').addClass('border-require');
        x++;
    }
    var religion_check = 0;
    if(religion_not_list_check.is(':checked')){
        var religion_check = 1;
    }
    if(religion_check==1 && (religion_not_list=='' || religion_not_list.length<3)){
        $('#editInfo #edit-info input[name="religion_not_list"]').addClass('border-require');
        x++;
    }

    if(x==0){
        var form_data = {
            id:id,
            lastname:lastname,
            firstname:firstname,
            middlename:middlename,
            extname:extname,
            nickname:nickname,
            sex:sex,
            civil_status:civil_status,
            dob:dob,
            birthplace:birthplace,
            country:country,
            citizenship:citizenship,
            religion:religion,
            religion_check:religion_check,
            religion_not_list:religion_not_list,
            nstp_serial_no:nstp_serial_no,
            branch:branch,
            department:department,
            program:program,
            curriculum:curriculum,
            grade_level:grade_level,
            student_status:student_status,
            url:'studentUpdateInfoSubmit'
        };
        submitUpdateInfo(thisBtn,form_data);
    }
}
function dataUpdateContact(thisBtn){
    var id = $('#studentViewModal input[name="id"]').val();
    var contact_no_1 = $('#editInfo #edit-contact input[name="contact_no_1"]').val();
    var contact_no_2 = $('#editInfo #edit-contact input[name="contact_no_2"]').val();
    var email_official = $('#editInfo #edit-contact input[name="email_official"]').val();
    var email = $('#editInfo #edit-contact input[name="email"]').val();
    var telephone_no = $('#editInfo #edit-contact input[name="telephone_no"]').val();
    var res_lot = $('#editInfo #edit-contact input[name="res_lot"]').val();
    var res_street = $('#editInfo #edit-contact input[name="res_street"]').val();
    var res_subd = $('#editInfo #edit-contact input[name="res_subd"]').val();
    var res_province_id = $('#editInfo #edit-contact select[name="res_province_id"] option:selected').val();
    var res_municipality_id = $('#editInfo #edit-contact select[name="res_municipality_id"] option:selected').val();
    var res_brgy_id = $('#editInfo #edit-contact select[name="res_brgy_id"] option:selected').val();
    var res_zip_code = $('#editInfo #edit-contact input[name="res_zip_code"]').val();
    var per_lot = $('#editInfo #edit-contact input[name="per_lot"]').val();
    var per_street = $('#editInfo #edit-contact input[name="per_street"]').val();
    var per_subd = $('#editInfo #edit-contact input[name="per_subd"]').val();
    var per_province_id = $('#editInfo #edit-contact select[name="per_province_id"] option:selected').val();
    var per_municipality_id = $('#editInfo #edit-contact select[name="per_municipality_id"] option:selected').val();
    var per_brgy_id = $('#editInfo #edit-contact select[name="per_brgy_id"] option:selected').val();
    var per_zip_code = $('#editInfo #edit-contact input[name="per_zip_code"]').val();
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var x = 0;

    $('#editInfo #edit-contact input[name="contact_no_1"]').removeClass('border-require');
    $('#editInfo #edit-contact input[name="email_official"]').removeClass('border-require');

    if(contact_no_1=='' || contact_no_1.length!=12){
        $('#editInfo #edit-contact input[name="contact_no_1"]').addClass('border-require');
        x++;
    }

    if(!emailRegex.test(email_official)){
        $('#editInfo #edit-contact input[name="email_official"]').addClass('border-require');
        x++;
    }

    var same_res = 'No';
    if($('#editInfo #edit-contact input[name="same_res"]').is(':checked')){
        var same_res = 'Yes';
    }

    if(x==0){
        var form_data = {
            id:id,
            contact_no_1:contact_no_1,
            contact_no_2:contact_no_2,
            email_official:email_official,
            email:email,
            telephone_no:telephone_no,
            res_lot:res_lot,
            res_street:res_street,
            res_subd:res_subd,
            res_province_id:res_province_id,
            res_municipality_id:res_municipality_id,
            res_brgy_id:res_brgy_id,
            res_zip_code:res_zip_code,
            same_res:same_res,
            per_lot:per_lot,
            per_street:per_street,
            per_subd:per_subd,
            per_province_id:per_province_id,
            per_municipality_id:per_municipality_id,
            per_brgy_id:per_brgy_id,
            per_zip_code:per_zip_code,
            url:'studentUpdateContactSubmit'
        };
        submitUpdateInfo(thisBtn,form_data);
    }
}
function dataUpdateEduc(thisBtn){
    var id = $('#studentViewModal input[name="id"]').val();
    var tab_x = $('#editInfo #edit-educ .educ-update-tab.show.active').data('x');

    if(tab_x=='a'){
        var tab_id = 'educ-new';
        var option = 'new';
    }else{
        var tab_id = 'educ-update'+tab_x;
        var option = 'update';
    }

    var educ_id = $('#editInfo #edit-educ #'+tab_id+' input[name="educ_id_'+option+'"]').val();
    var level = $('#editInfo #edit-educ #'+tab_id+' select[name="level_'+option+'"] option:selected').val();
    var school_not_list_check = $('#editInfo #edit-educ #'+tab_id+' input[name="school_not_list_check_'+option+'"]');
    var school = $('#editInfo #edit-educ #'+tab_id+' select[name="school_'+option+'"] option:selected').val();
    var school_not_list = $('#editInfo #edit-educ #'+tab_id+' input[name="school_not_list_'+option+'"]').val();
    var school_shorten_not_list = $('#editInfo #edit-educ #'+tab_id+' input[name="school_shorten_not_list_'+option+'"]').val();
    var program_not_list_check = $('#editInfo #edit-educ #'+tab_id+' input[name="program_not_list_check_'+option+'"]');
    var program_educ = $('#editInfo #edit-educ #'+tab_id+' select[name="program_educ_'+option+'"] option:selected').val();
    var program_not_list = $('#editInfo #edit-educ #'+tab_id+' input[name="program_not_list_'+option+'"]').val();
    var period_from = $('#editInfo #edit-educ #'+tab_id+' input[name="period_from_'+option+'"]').val();
    var period_to = $('#editInfo #edit-educ #'+tab_id+' input[name="period_to_'+option+'"]').val();
    var period_to_present = $('#editInfo #edit-educ #'+tab_id+' input[name="period_to_present_'+option+'"]');
    var units_earned = $('#editInfo #edit-educ #'+tab_id+' input[name="units_earned_'+option+'"]').val();
    var year_grad = $('#editInfo #edit-educ #'+tab_id+' input[name="year_grad_'+option+'"]').val();
    var honors = $('#editInfo #edit-educ #'+tab_id+' input[name="honors_'+option+'"]').val();
    var x = 0;

    var school_check = 0;
    if(school_not_list_check.is(':checked')){
        var school_check = 1;
    }
    var program_check = 0;
    if(program_not_list_check.is(':checked')){
        var program_check = 1;
    }
    var present = 0;
    if(period_to_present.is(':checked')){
        var present = 1;
    }

    $('#editInfo #edit-educ #'+tab_id+' input[name="school_not_list_'+option+'"]').removeClass('border-require');
    $('#editInfo #edit-educ #'+tab_id+' input[name="school_shorten_not_list_'+option+'"]').removeClass('border-require');
    $('#editInfo #edit-educ #'+tab_id+' input[name="program_not_list_'+option+'"]').removeClass('border-require');

    if(school_check==1 && (school_not_list=='' || school_not_list.length<3)){
        $('#editInfo #edit-educ #'+tab_id+' input[name="school_not_list_'+option+'"]').addClass('border-require');
        $('#editInfo #edit-educ #'+tab_id+' input[name="school_shorten_not_list_'+option+'"]').addClass('border-require');
        x++;
    }
    if(program_check==1 && (program_not_list=='' || program_not_list.length<3)){
        $('#editInfo #edit-educ #'+tab_id+' input[name="program_not_list_'+option+'"]').addClass('border-require');
        x++;
    }

    if(x==0){
        var form_data = {
            id:id,
            educ_id:educ_id,
            level:level,
            school_check:school_check,
            school:school,
            school_not_list:school_not_list,
            school_shorten_not_list:school_shorten_not_list,
            program_check:program_check,
            program_educ:program_educ,
            program_not_list:program_not_list,
            period_from:period_from,
            period_to:period_to,
            present:present,
            units_earned:units_earned,
            year_grad:year_grad,
            honors:honors,
            option:option,
            url:'studentUpdateEducSubmit'
        };
        submitUpdateInfo(thisBtn,form_data);
    }
}
function dataUpdateFam(thisBtn){
    var id = $('#studentViewModal input[name="id"]').val();
    var tab_x = $('#editInfo #edit-fam .fam-update-tab.show.active').data('x');
    var x = 0;

    if(tab_x=='a'){
        var tab_id = 'fam-new';
        var option = 'new';
    }else{
        var tab_id = 'fam-update-'+tab_x;
        var option = 'update';
    }

    var fam_id = $('#editInfo #edit-fam #'+tab_id+' input[name="fam_id"]').val();
    var fam_relation = $('#editInfo #edit-fam #'+tab_id+' select[name="fam_relation"] option:selected').val();
    var lastname = $('#editInfo #edit-fam #'+tab_id+' input[name="fam_lastname"]').val();
    var firstname = $('#editInfo #edit-fam #'+tab_id+' input[name="fam_firstname"]').val();
    var middlename = $('#editInfo #edit-fam #'+tab_id+' input[name="fam_middlename"]').val();
    var extname = $('#editInfo #edit-fam #'+tab_id+' input[name="fam_extname"]').val();
    var dob = $('#editInfo #edit-fam #'+tab_id+' input[name="fam_dob"]').val();
    var contact_no = $('#editInfo #edit-fam #'+tab_id+' input[name="fam_contact_no"]').val();
    var email = $('#editInfo #edit-fam #'+tab_id+' input[name="fam_email"]').val();
    var occupation = $('#editInfo #edit-fam #'+tab_id+' input[name="fam_occupation"]').val();
    var employer = $('#editInfo #edit-fam #'+tab_id+' input[name="fam_employer"]').val();
    var employer_address = $('#editInfo #edit-fam #'+tab_id+' input[name="fam_employer_address"]').val();
    var employer_contact = $('#editInfo #edit-fam #'+tab_id+' input[name="fam_employer_contact"]').val();

    $('#editInfo #edit-fam #'+tab_id+' input[name="fam_lastname"]').removeClass('border-require');
    $('#editInfo #edit-fam #'+tab_id+' input[name="fam_firstname"]').removeClass('border-require');

    if(lastname==''){
        $('#editInfo #edit-fam #'+tab_id+' input[name="fam_lastname"]').addClass('border-require');
        x++;
    }
    if(firstname==''){
        $('#editInfo #edit-fam #'+tab_id+' input[name="fam_firstname"]').addClass('border-require');
        x++;
    }

    if(x==0){
        var form_data = {
            id:id,
            fam_id:fam_id,
            fam_relation:fam_relation,
            lastname:lastname,
            firstname:firstname,
            middlename:middlename,
            extname:extname,
            dob:dob,
            contact_no:contact_no,
            email:email,
            occupation:occupation,
            employer:employer,
            employer_address:employer_address,
            employer_contact:employer_contact,
            option:option,
            url:'studentUpdateFamSubmit'
        };
        submitUpdateInfo(thisBtn,form_data);
    }
}
function submitUpdateInfo(thisBtn,form_data){

    $.ajax({
        url: base_url+'/rims/student/'+form_data.url,
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
                toastr.success(data.result);
            }else{
                toastr.error(data.result);
            }
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
}
function schoolSearch1(x){
    $(document).ready(function() {
        $(".schoolSearch1"+x).select2({
            dropdownParent: $("#schoolSearch1"+x),
            ajax: {
            url: base_url+'/search/school1',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                _token: CSRF_TOKEN,
                search: params.term
                };
            },
            processResults: function (response) {
                return {
                results: response
                };
            },
            cache: true
            }
        });
    });
}
function programSearch4(level,school,x){
    if(school){
        $(document).ready(function() {
            $(".programSearch2"+x).select2({
                dropdownParent: $("#programSearch2"+x),
                ajax: {
                url: base_url+'/search/programSearch2',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term,
                        level:level,
                        school:school
                    };
                },
                processResults: function (response) {
                    return {
                    results: response
                    };
                },
                cache: true
                }
            });
        });
    }
}


