function psgc_brgys(city_muns,idClass){
    $(document).ready(function() {
        $("."+idClass).select2({
            dropdownParent: $("#"+idClass),
            ajax: { 
            url: base_url+'/search/psgcBrgys',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                _token: CSRF_TOKEN,
                city_muns:city_muns,
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