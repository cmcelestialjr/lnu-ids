_information('schedule',$('#displayDiv'),'');
function _information(url,thisBtn,active){
    var id = $('input[name="id_no"]').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/'+url,
        tid:'displayDiv',
        id:id,
        active:active,
        from_sys:'fis'
    };
    loadDivwLoader(form_data,thisBtn);
}