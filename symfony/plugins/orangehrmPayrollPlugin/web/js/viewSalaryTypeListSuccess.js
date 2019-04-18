$(document).ready(function() {
    $('#btnAdd').click(function() {       
        location.href = url_AddSalaryComponent;
    });
    
    $('#contractEdidMode').hide();
        
    $('#btnDelete').attr('disabled', 'disabled');
        
    $("#ohrmList_chkSelectAll").click(function() {
        if($(":checkbox").length == 1) {
            $('#btnDelete').attr('disabled','disabled');
        }
        else {
            if($("#ohrmList_chkSelectAll").is(':checked')) {
                $('#btnDelete').removeAttr('disabled');
            } else {
                $('#btnDelete').attr('disabled','disabled');
            }
        }
    });

    $(':checkbox[name*="chkSelectRow[]"]').click(function() {
        if($(':checkbox[name*="chkSelectRow[]"]').is(':checked')) {
            $('#btnDelete').removeAttr('disabled');
        } else {
            $('#btnDelete').attr('disabled','disabled');
        }
    });

    
    $('#dialogDeleteBtn').click(function() {
        $('#frmList_ohrmListComponent').submit();
    });

});