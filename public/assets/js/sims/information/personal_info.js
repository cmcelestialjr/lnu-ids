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
$(document).off('click', '#address button[name="submit"]').on('click', '#address button[name="submit"]', function (e) {
    var thisBtn = $(this);
    addressSubmit(thisBtn);
});
$(document).off('click', '#address #new_religion').on('click', '#address #new_religion', function (e) {
    var thisBtn = $(this);
    new_religion(thisBtn);
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
function addressSubmit(thisBtn){
    var sex = $('#address select[name="sex"] option:selected').val();
    var civil_status = $('#address select[name="civil_status"] option:selected').val();
    var contact_no = $('#address input[name="contact_no"]').val();
    var contact_no_official = $('#address input[name="contact_no_official"]').val();
    var email = $('#address input[name="email"]').val();
    var email_official = $('#address input[name="email_official"]').val();
    var blood_type = $('#address select[name="blood_type"] option:selected').val();
    var religion = $('#address select[name="religion"] option:selected').val();
    var res_lot = $('#address input[name="res_lot"]').val();
    var res_street = $('#address input[name="res_street"]').val();
    var res_subd = $('#address input[name="res_subd"]').val();
    var res_zip_code = $('#address input[name="res_zip_code"]').val();
    var res_brgy_id = $('#address select[name="res_brgy_id"] option:selected').val();
    var res_municipality_id = $('#address select[name="res_municipality_id"] option:selected').val();
    var res_province_id = $('#address select[name="res_province_id"] option:selected').val();
    var same_res = $('#address input[name="same_res"]');    
    var new_religion = $('#address input[name="new_religion"]').val();
    var check_religion = 0;
    var x = 0;
    $('#address input[name="new_religion"]').removeClass('border-require');

    if($('#address input[name="check_religion"]').is(':checked')){
        var check_religion = 1;
        if(new_religion==''){
            $('#address input[name="new_religion"]').addClass('border-require');
            x++;
        }
    }

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
    if(x==0){
        var form_data = {
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
            per_province_id:per_province_id,
            sex:sex,
            civil_status:civil_status,
            contact_no:contact_no.replace("_", ""),
            contact_no_official:contact_no_official.replace("_", ""),
            email:email,
            email_official:email_official,
            blood_type:blood_type,
            religion:religion,
            check_religion:check_religion,
            new_religion:new_religion
        };
        $.ajax({
            url: base_url+'/sims/information/personalInfoSubmit',
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
                $('#address input[name="email"]').removeClass('border-require');
                $('#address input[name="email_official"]').removeClass('border-require');
                $('#address input[name="contact_no"]').removeClass('border-require');
                $('#address input[name="contact_no_official"]').removeClass('border-require');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading'); 
                if(data.result=='success'){
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
                    informationPersonalInfo();
                }else if(data.result=='errors'){
                    if(data.email!=''){
                        toastr.error(data.email);
                        $('#address input[name="email"]').addClass('border-require');
                    }
                    if(data.email_official!=''){
                        toastr.error(data.email_official);
                        $('#address input[name="email_official"]').addClass('border-require');
                    }
                    if(data.contact_no!=''){
                        toastr.error(data.contact_no);
                        $('#address input[name="contact_no"]').addClass('border-require');
                    }
                    if(data.contact_no_official!=''){
                        toastr.error(data.contact_no_official);
                        $('#address input[name="contact_no_official"]').addClass('border-require');
                    }
                    thisBtn.addClass('input-error');                    
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
function new_religion(thisBtn){
    $('#address #list_religion_div').removeClass('hide');
    $('#address #new_religion_div').addClass('hide');
    if(thisBtn.is(':checked')){
        $('#address #new_religion_div').removeClass('hide');
        $('#address #list_religion_div').addClass('hide');
    }
}
function informationPersonalInfo(){
    var thisBtn = $('#informationPersonalInfo');
    $.ajax({
        url: base_url+'/sims/information/informationPersonalInfo',
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