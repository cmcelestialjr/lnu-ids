function ludongStudentSearch(){
    $(document).ready(function() {
        $(".ludongStudent").select2({
            dropdownParent: $("#ludongStudent"),
            ajax: { 
            url: base_url+'/search/ludongStudent',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                _token: CSRF_TOKEN,
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