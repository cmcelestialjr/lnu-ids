function courseSearch(school_year){
    $(document).ready(function() {
        $(".courseCode").select2({
            dropdownParent: $("#courseCode"),
            ajax: { 
            url: base_url+'/search/courseCode',
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