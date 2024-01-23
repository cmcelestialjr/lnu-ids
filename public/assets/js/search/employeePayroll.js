function employeePayrollSearch(id,code){
    $(document).ready(function() {
        $(".employeePayrollSearch").select2({
            dropdownParent: $("#employeePayroll"),
            ajax: { 
            url: base_url+'/search/employeePayroll',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                _token: CSRF_TOKEN,
                id:id,
                code:code,
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