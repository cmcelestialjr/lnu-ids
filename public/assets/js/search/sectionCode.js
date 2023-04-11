function courseSearch(school_year){
    $(document).ready(function() {
        $(".sectionCode").select2({
            dropdownParent: $("#sectionCode"),
            ajax: { 
            url: base_url+'/search/sectionCode',
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