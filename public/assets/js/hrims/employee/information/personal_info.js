var res_city_muns_id = $('#address select[name="res_municipality_id"] option:selected').val();
var res_province_id = $('#address select[name="res_province_id"] option:selected').val();
var per_city_muns_id = $('#address select[name="per_municipality_id"] option:selected').val();
var per_province_id = $('#address select[name="per_province_id"] option:selected').val();
var province = 'place';
var region = 'none';
psgc_city_muns(province,'psgcCityMunsPlace');
psgc_brgys(res_city_muns_id,'psgcBrgysRes');
psgc_city_muns(res_province_id,'psgcCityMunsRes');
psgc_provinces(region,'psgcProvinceRes');

psgc_brgys(per_city_muns_id,'psgcBrgysPer');
psgc_city_muns(per_province_id,'psgcCityMunsPer');
psgc_provinces(region,'psgcProvincePer');

$(document).off('change', '#address select[name="res_province_id"]').on('change', '#address select[name="res_province_id"]', function (e) {
    var res_province_id = $(this).val();
    psgc_city_muns(res_province_id,'psgcCityMunsRes');
    $('#address select[name="res_brgy_id"]').empty();
    $('#address select[name="res_municipality_id"]').empty();
});
$(document).off('change', '#address select[name="res_municipality_id"]').on('change', '#address select[name="res_municipality_id"]', function (e) {
    var res_city_muns_id = $(this).val();
    psgc_brgys(res_city_muns_id,'psgcBrgysRes');
    $('#address select[name="res_brgy_id"]').empty();
});

$(document).off('change', '#address select[name="per_province_id"]').on('change', '#address select[name="per_province_id"]', function (e) {
    var per_province_id = $(this).val();
    psgc_city_muns(per_province_id,'psgcCityMunsPer');
    $('#address select[name="per_brgy_id"]').empty();
    $('#address select[name="per_municipality_id"]').empty();
});
$(document).off('change', '#address select[name="per_municipality_id"]').on('change', '#address select[name="per_municipality_id"]', function (e) {
    var per_city_muns_id = $(this).val();
    psgc_brgys(per_city_muns_id,'psgcBrgysPer');
    $('#address select[name="per_brgy_id"]').empty();
});
$(document).off('click', '#address input[name="same_res"]').on('click', '#address input[name="same_res"]', function (e) {
    var thisBtn = $(this);
    same_res(thisBtn);
});
$(document).off('click', '#info button[name="submit"]').on('click', '#info button[name="submit"]', function (e) {
    var thisBtn = $(this);
    infoSubmit(thisBtn);
});
$(document).off('click', '#address button[name="submit"]').on('click', '#address button[name="submit"]', function (e) {
    var thisBtn = $(this);
    addressSubmit(thisBtn);
});
$(document).off('click', '#id_no button[name="submit"]').on('click', '#id_no button[name="submit"]', function (e) {
    var thisBtn = $(this);
    idNoSubmit(thisBtn);
});
function same_res(thisBtn){
    var res_lot = $('#address input[name="res_lot"]').val();
    var res_street = $('#address input[name="res_street"]').val();
    var res_subd = $('#address input[name="res_subd"]').val();
    var res_zip_code = $('#address input[name="res_zip_code"]').val();
    var res_brgy_id = $('#address select[name="res_brgy_id"] option:selected').val();
    var res_city_muns_id = $('#address select[name="res_municipality_id"] option:selected').val();
    var res_province_id = $('#address select[name="res_province_id"] option:selected').val();
    var res_brgy_id_text = $('#address select[name="res_brgy_id"] option:selected').text();
    var res_city_muns_id_text = $('#address select[name="res_municipality_id"] option:selected').text();
    var res_province_id_text = $('#address select[name="res_province_id"] option:selected').text();

    if(thisBtn.data('num')==0){
        var per_lot = $('#address input[name="per_lot"]').val();
        var per_street = $('#address input[name="per_street"]').val();
        var per_subd = $('#address input[name="per_subd"]').val();
        var per_zip_code = $('#address input[name="per_zip_code"]').val();
        var per_brgy_id = $('#address select[name="per_brgy_id"] option:selected').val();
        var per_city_muns_id = $('#address select[name="per_municipality_id"] option:selected').val();
        var per_province_id = $('#address select[name="per_province_id"] option:selected').val();
        var per_brgy_id_text = $('#address select[name="per_brgy_id"] option:selected').text();
        var per_city_muns_id_text = $('#address select[name="per_municipality_id"] option:selected').text();
        var per_province_id_text = $('#address select[name="per_province_id"] option:selected').text();

        $('#address input[name="per_lot"]').data('val',per_lot);
        $('#address input[name="per_street"]').data('val',per_street);
        $('#address input[name="per_subd"]').data('val',per_subd);
        $('#address input[name="per_zip_code"]').data('val',per_zip_code);
        $('#address select[name="per_brgy_id_"]').append($('<option></option>').val(per_brgy_id).text(per_brgy_id_text));
        $('#address select[name="per_municipality_id_"]').append($('<option></option>').val(per_city_muns_id).text(per_city_muns_id_text));
        $('#address select[name="per_province_id_"]').append($('<option></option>').val(per_province_id).text(per_province_id_text));
    }

    thisBtn.data('num',1);
    $('#address select[name="per_brgy_id"]').empty();
    $('#address select[name="per_municipality_id"]').empty();
    $('#address select[name="per_province_id"]').empty();

    var per_lot = $('#address input[name="per_lot"]').data('val');
    var per_street = $('#address input[name="per_street"]').data('val');
    var per_subd = $('#address input[name="per_subd"]').data('val');
    var per_zip_code = $('#address input[name="per_zip_code"]').data('val');
    var per_brgy_id = $('#address select[name="per_brgy_id_"] option:selected').val();
    var per_city_muns_id = $('#address select[name="per_municipality_id_"] option:selected').val();
    var per_province_id = $('#address select[name="per_province_id_"] option:selected').val();
    var per_brgy_id_text = $('#address select[name="per_brgy_id_"] option:selected').text();
    var per_city_muns_id_text = $('#address select[name="per_municipality_id_"] option:selected').text();
    var per_province_id_text = $('#address select[name="per_province_id_"] option:selected').text();

    if(thisBtn.is(':checked')){
        $('#address input[name="per_lot"]').val(res_lot);
        $('#address input[name="per_street"]').val(res_street);
        $('#address input[name="per_subd"]').val(res_subd);
        $('#address input[name="per_zip_code"]').val(res_zip_code);
        $('#address select[name="per_brgy_id"]').append($('<option></option>').val(res_brgy_id).text(res_brgy_id_text));
        $('#address select[name="per_municipality_id"]').append($('<option></option>').val(res_city_muns_id).text(res_city_muns_id_text));
        $('#address select[name="per_province_id"]').append($('<option></option>').val(res_province_id).text(res_province_id_text));

        $('#address input[name="per_lot"]').attr("disabled", true);
        $('#address input[name="per_street"]').attr("disabled", true);
        $('#address input[name="per_subd"]').attr("disabled", true);
        $('#address input[name="per_zip_code"]').attr("disabled", true);
        $('#address select[name="per_brgy_id"]').attr("disabled", true);
        $('#address select[name="per_municipality_id"]').attr("disabled", true);
        $('#address select[name="per_province_id"]').attr("disabled", true);
    }else{
        $('#address input[name="per_lot"]').val(per_lot);
        $('#address input[name="per_street"]').val(per_street);
        $('#address input[name="per_subd"]').val(per_subd);
        $('#address input[name="per_zip_code"]').val(per_zip_code);
        $('#address select[name="per_brgy_id"]').append($('<option></option>').val(per_brgy_id).text(per_brgy_id_text));
        $('#address select[name="per_municipality_id"]').append($('<option></option>').val(per_city_muns_id).text(per_city_muns_id_text));
        $('#address select[name="per_province_id"]').append($('<option></option>').val(per_province_id).text(per_province_id_text));

        $('#address input[name="per_lot"]').attr("disabled", false);
        $('#address input[name="per_street"]').attr("disabled", false);
        $('#address input[name="per_subd"]').attr("disabled", false);
        $('#address input[name="per_zip_code"]').attr("disabled", false);
        $('#address select[name="per_brgy_id"]').attr("disabled", false);
        $('#address select[name="per_municipality_id"]').attr("disabled", false);
        $('#address select[name="per_province_id"]').attr("disabled", false);
    }
}
function infoSubmit(thisBtn){
    var id_no = $('#employeeInformationModal input[name="id_no"]').val();
    var honorific = $('#info input[name="honorific"]').val();
    var post_nominal = $('#info input[name="post_nominal"]').val();
    var lastname = $('#info input[name="lastname"]').val();
    var firstname = $('#info input[name="firstname"]').val();
    var middlename = $('#info input[name="middlename"]').val();
    var extname = $('#info input[name="extname"]').val();
    var middlename_in_last = $('#info select[name="middlename_in_last"] option:selected').val();
    var dob = $('#info input[name="dob"]').val();
    var place_birth = $('#info select[name="place_birth"] option:selected').val();
    var sex = $('#info select[name="sex"] option:selected').val();
    var civil_status = $('#info select[name="civil_status"] option:selected').val();
    var height = $('#info input[name="height"]').val();
    var weight = $('#info input[name="weight"]').val();
    var blood_type = $('#info select[name="blood_type"] option:selected').val();
    var telephone_no = $('#info input[name="telephone_no"]').val();
    var contact_no = $('#info input[name="contact_no"]').val();
    var contact_no_official = $('#info input[name="contact_no_official"]').val();
    var email = $('#info input[name="email"]').val();
    var email_official = $('#info input[name="email_official"]').val();
    var x = 0;
    if(lastname==''){
        toastr.error('Please input Lastname');
        $('#info input[name="lastname"]').addClass('border-require');
        x++;
    }
    if(firstname==''){
        toastr.error('Please input Firstname');
        $('#info input[name="firstname"]').addClass('border-require');
        x++;
    }
    if(dob==''){
        toastr.error('Please input Birthdate');
        $('#info input[name="bdate"]').addClass('border-require');
        x++;
    }
    if(sex==''){
        toastr.error('Please select Sex');
        $('#info select[name="sex"]').addClass('border-require');
        x++;
    }
    if(civil_status==''){
        toastr.error('Please select Civil Status');
        $('#info select[name="civil_status"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id_no:id_no,
            honorific:honorific,
            post_nominal:post_nominal,
            lastname:lastname,
            firstname:firstname,
            middlename:middlename,
            extname:extname,
            middlename_in_last:middlename_in_last,
            dob:dob,
            place_birth:place_birth,
            sex:sex,
            civil_status:civil_status,
            height:height,
            weight:weight,
            blood_type:blood_type,
            telephone_no:telephone_no,
            contact_no:contact_no,
            contact_no_official:contact_no_official,
            email:email,
            email_official:email_official
        };
        $.ajax({
            url: base_url+'/hrims/employee/information/infoSubmit',
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
function addressSubmit(thisBtn){
    var id_no = $('#employeeInformationModal input[name="id_no"]').val();
    var res_lot = $('#address input[name="res_lot"]').val();
    var res_street = $('#address input[name="res_street"]').val();
    var res_subd = $('#address input[name="res_subd"]').val();
    var res_zip_code = $('#address input[name="res_zip_code"]').val();
    var res_brgy_id = $('#address select[name="res_brgy_id"] option:selected').val();
    var res_municipality_id = $('#address select[name="res_municipality_id"] option:selected').val();
    var res_province_id = $('#address select[name="res_province_id"] option:selected').val();
    var same_res = $('#address input[name="same_res"]');

    if(same_res.is(':checked')){
        var same_res = 'Yes';
        var per_lot = res_lot;
        var per_street = res_street;
        var per_subd = res_subd;
        var per_zip_code = res_zip_code;
        var per_brgy_id = res_brgy_id;
        var per_municipality_id = res_municipality_id;
        var per_province_id = res_province_id;
    }else{
        var same_res = 'No';
        var per_lot = $('#address input[name="per_lot"]').val();
        var per_street = $('#address input[name="per_street"]').val();
        var per_subd = $('#address input[name="per_subd"]').val();
        var per_zip_code = $('#address input[name="per_zip_code"]').val();
        var per_brgy_id = $('#address select[name="per_brgy_id"] option:selected').val();
        var per_municipality_id = $('#address select[name="per_municipality_id"] option:selected').val();
        var per_province_id = $('#address select[name="per_province_id"] option:selected').val();
    }
    var form_data = {
        id_no:id_no,
        res_lot:res_lot,
        res_street:res_street,
        res_subd:res_subd,
        res_zip_code:res_zip_code,
        res_brgy_id:res_brgy_id,
        res_municipality_id:res_municipality_id,
        res_province_id:res_province_id,
        same_res:same_res,
        per_lot:per_lot,
        per_street:per_street,
        per_subd:per_subd,
        per_zip_code:per_zip_code,
        per_brgy_id:per_brgy_id,
        per_municipality_id:per_municipality_id,
        per_province_id:per_province_id
    };
    $.ajax({
        url: base_url+'/hrims/employee/information/addressSubmit',
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
function idNoSubmit(thisBtn){
    var id_no = $('#employeeInformationModal input[name="id_no"]').val();
    var bank_account_no = $('#id_no input[name="bank_account_no"]').val();
    var tin_no = $('#id_no input[name="tin_no"]').val();
    var gsis_bp_no = $('#id_no input[name="gsis_bp_no"]').val();
    var philhealth_no = $('#id_no input[name="philhealth_no"]').val();
    var sss_no = $('#id_no input[name="sss_no"]').val();
    var pagibig_no = $('#id_no input[name="pagibig_no"]').val();
    var pagibig2_no = $('#id_no input[name="pagibig2_no"]').val();
    var pagibig_mpl_app_no = $('#id_no input[name="pagibig_mpl_app_no"]').val();
    var pagibig_cal_app_no = $('#id_no input[name="pagibig_cal_app_no"]').val();
    var pagibig_housing_app_no = $('#id_no input[name="pagibig_housing_app_no"]').val();
    var pagibig_pag2_app_no = $('#id_no input[name="pagibig_pag2_app_no"]').val();

    var form_data = {
        id_no:id_no,
        bank_account_no:bank_account_no,
        tin_no:tin_no,
        gsis_bp_no:gsis_bp_no,
        philhealth_no:philhealth_no,
        sss_no:sss_no,
        pagibig_no:pagibig_no,
        pagibig2_no:pagibig2_no,
        pagibig_mpl_app_no:pagibig_mpl_app_no,
        pagibig_cal_app_no:pagibig_cal_app_no,
        pagibig_housing_app_no:pagibig_housing_app_no,
        pagibig_pag2_app_no:pagibig_pag2_app_no
    };
    $.ajax({
        url: base_url+'/hrims/employee/information/idNoSubmit',
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
