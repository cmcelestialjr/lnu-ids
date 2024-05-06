function programSearch2(level,school){
    $(document).ready(function() {
        $(".programSearch2").select2({
            dropdownParent: $("#programSearch2"),
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
