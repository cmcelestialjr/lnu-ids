function psgc_city_muns(province,idClass){
    $(document).ready(function() {
        $("."+idClass).select2({
            dropdownParent: $("#"+idClass),
            ajax: { 
            url: base_url+'/search/psgcCityMuns',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                _token: CSRF_TOKEN,
                province:province,
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