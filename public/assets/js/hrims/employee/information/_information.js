$(document).off('click', '#employeeInformationModal .buttonDisp').on('click', '#employeeInformationModal .buttonDisp', function (e) {
    var thisBtn = $(this);
    var t = thisBtn.data('t');
    var url = '';
    var active = '';
    if(t=='info'){
        var url = 'personalInfo';
    }else if(t=='fam'){
        var url = 'familyInfo';
    }else if(t=='educ'){
        var url = 'educInfo';
    }else if(t=='elig'){
        var url = 'eligInfo';
    }else if(t=='exp'){
        var url = 'expInfo';
    }else if(t=='volun'){
        var url = 'volunInfo';
    }else if(t=='train'){
        var url = 'learnInfo';
    }else if(t=='other'){
        var url = 'otherInfo';
    }else if(t=='doc'){
        var url = 'docInfo';
    }else if(t=='sched'){
        var url = 'schedule';
    }
    if(url!=''){
        $('#employeeInformationModal .buttonDisp').removeClass('btn-primary-scan-active');
        _information(url,thisBtn,active);
        thisBtn.addClass('btn-primary-scan-active');
    }
});
function _information(url,thisBtn,active){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/'+url,
        tid:'displayDiv',
        id:id,
        active:active,
        from_sys:'hr'
    };
    loadDivwLoader(form_data,thisBtn);
}
