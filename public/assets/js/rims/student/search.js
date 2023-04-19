$(document).ready(function() {
    var school_year_id = $('select[name="school_year"] option:selected').val();
    $(".search_student").select2({
        dropdownParent: $("#students"),
        ajax: { 
          url: base_url+'/rims/student/searchStudents',
          type: "post",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
               _token: CSRF_TOKEN,
               school_year_id:school_year_id,
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