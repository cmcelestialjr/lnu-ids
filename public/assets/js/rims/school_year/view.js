view_school_year();
$(document).on('change', '#programsDiv select[name="departments"]', function (e) {
    var departments = $(this).val();
    $('#programsDiv .livewire-loader').html('<br><img src="'+base_url+'/assets/images/loader/loader-dots.gif" style="height: 60%;width:60%">');
    $('#programsDiv .livewire-table').addClass('hide');
    Livewire.emit('updatedDepartments', departments);
});
$(document).on('change', '#coursesViewModal select[name="curriculum"]', function (e) {
    courses_view_modal();
});
$(document).on('click', '#schoolYearDiv #schoolYearList', function (e) {
    view_school_year();
});