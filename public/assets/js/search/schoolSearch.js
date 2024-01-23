schoolSearch();
function schoolSearch(){
    $(document).ready(function() {
        $(".schoolSearch").select2({
            dropdownParent: $("#schoolSearch"),
            ajax: { 
            url: base_url+'/search/school',
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