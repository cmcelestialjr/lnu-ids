
$(document).off('click', '#curriculumDiv #curriculumTable .courseUpdate').on('click', '#curriculumDiv #curriculumTable .courseUpdate', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/courseUpdate';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/programs/courseTablePre',
        tid:'courseTablePre',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).off('click', '#curriculumModal #curriculumDiv button[name="newCourse"]').on('click', '#curriculumModal #curriculumDiv button[name="newCourse"]', function (e) {
    var thisBtn = $(this);
    var id = $('#curriculumModal #curriculumDiv select[name="curriculum"] option:selected').val();
    var url = base_url+'/rims/programs/newCourse';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/programs/curriculumTablePre',
        tid:'curriculumTablePre',
        id:id,
        level:'All',
        status:'All'
    };
    loadModal(form_data,thisBtn);
});
$(document).off('click', '#curriculumModal button[name="curriculumNew"]').on('click', '#curriculumModal button[name="curriculumNew"]', function (e) {
    var thisBtn = $(this);
    var id = $('#curriculumModal input[name="id"]').val();
    var url = base_url+'/rims/programs/curriculumNewModal';
    var modal = 'primary';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id
    };
    loadModal(form_data,thisBtn);
});
