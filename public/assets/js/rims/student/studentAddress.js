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
