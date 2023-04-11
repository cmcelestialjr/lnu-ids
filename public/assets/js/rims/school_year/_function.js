function courses_view_modal(){
    var thisBtn = $('#coursesViewModal select[name="curriculum"]');
    var curriculum_id = $('#coursesViewModal select[name="curriculum"] option:selected').val();
    var id = $('#coursesViewModal input[name="id"]').val();
    var form_data = {
        url_table:base_url+'/rims/schoolYear/curriculumViewList',
        tid:'curriculumViewList',
        id:id,
        curriculum_id:curriculum_id,
        type:'select'
    };
    loadDivwLoader(form_data,thisBtn);
}
function view_programs(id,thisBtn){
    var url = base_url+'/rims/schoolYear/programs';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var livewire_emit = 'shoolYearIDs';
    var livewire_value = [id];
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'true',
        w_table:'wo',
        id:id,
        livewire:'w',
        livewire_emit:livewire_emit,
        livewire_value:livewire_value
    };
    loadModal(form_data,thisBtn);
}
function view_school_year(){
    var form_data = {
        url_table:base_url+'/rims/schoolYear/viewTable',
        tid:'viewTable',
        id:''
    };
    loadTable(form_data);
}