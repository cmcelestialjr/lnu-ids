student_table();
$(document).on('change', '#studentDiv #list select', function (e) {
    var option = $('#studentDiv #list select[name="option"] option:selected').val();
    $('#studentDiv #list #date_graduate').addClass('hide');
    $('#studentDiv #list #school_year').removeClass('hide');
    if(option=='Graduated'){
        $('#studentDiv #list #date_graduate').removeClass('hide');
        $('#studentDiv #list #school_year').addClass('hide');
    }
    student_table();
});
$(document).on('change', '#studentTORModal select[name="level"]', function (e) {
    var thisBtn = $(this);
    var id = $('#studentViewModal input[name="id"]').val();
    var program_level = $('#studentTORModal select[name="level"] option:selected').val();    
    torDiv(id,program_level,thisBtn);
});
