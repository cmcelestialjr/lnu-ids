function unitByDepartment(department_id){
    $(document).ready(function() {
        $(".unitByDepartment").select2({
            dropdownParent: $("#unitByDepartment"),
            ajax: { 
            url: base_url+'/search/unitByDepartment',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                _token: CSRF_TOKEN,
                department_id:department_id,
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