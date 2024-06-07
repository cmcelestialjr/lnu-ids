employeeSearch();
function employeeSearch(){
    $(document).ready(function() {
        $(".employeeSearch").select2({
            dropdownParent: $("#employeeSearch"),
            ajax: {
            url: base_url+'/search/employeeSearch',
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
