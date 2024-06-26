employeeDesignation();
function employeeDesignation(){
    $(document).ready(function() {
        $(".employeeDesignation").select2({
            dropdownParent: $("#designationNewModal"),
            ajax: { 
            url: base_url+'/search/employeeDesignation',
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