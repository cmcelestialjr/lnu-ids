_information('schedule',$('#table .schedNewModal'),'table_active');
function _information(url,thisBtn,active){    
    var id = $('input[name="user_information"]').val();
    var year = $('#dtrDiv select[name="year"] option:selected').val();
    var month = $('#dtrDiv select[name="month"] option:selected').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/'+url,
        tid:'displayDiv',
        id:id,
        active:active,
        from_sys:'dtr',
        year:year,
        month:month
    };
    loadDivwLoader(form_data,thisBtn);
}