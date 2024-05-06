function psgc_provinces(region,idClass){
    $(document).ready(function() {
        $("."+idClass).select2({
            dropdownParent: $("#"+idClass),
            ajax: {
                url: base_url+'/search/psgcProvinces',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: CSRF_TOKEN,
                        region:region,
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
