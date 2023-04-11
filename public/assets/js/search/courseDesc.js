function courseSearch(school_year){
    $(document).ready(function() {
        $(".courseDesc").select2({
            dropdownParent: $("#courseDesc"),
            ajax: { 
            url: base_url+'/search/courseDesc',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                _token: CSRF_TOKEN,
                school_year:school_year,
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