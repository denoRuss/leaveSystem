$(document).ready(function() {
    $('#btnCancel').click(function() {
        location.href = url_TaxBracketList;
        //validator.resetForm();
    });
    
    var validator = $('#frmPayrollConfiguration').validate({
        rules: {

            'payroll_configuration[epf_percentage]': {
                required: true,
                twoDecimals:true

            },
            'payroll_configuration[etf_percentage]': {
                required: true,
                twoDecimals:true

            },


        },
        messages: {

            'payroll_configuration[epf_percentage]': {
                required: lang_Required,
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'payroll_configuration[etf_percentage]': {
                required: lang_Required,
                twoDecimals: lang_salaryShouldBeNumeric,
            },

        },
        errorPlacement: function(error, element) {
            if (element.is('#salary_component_value_type_1')) {
                error.insertAfter($('#salary_component_value_type_2').parent().parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $.validator.addMethod("twoDecimals", function(value, element, params) {

        var isValid = false;
        var maxSal = value;//$('#payGradeCurrency_maxSalary').val();
        var match = maxSal.match(/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/);
        if(match) {
            isValid = true;
        }
        if (maxSal == ""){
            isValid = true;
        }
        return isValid;
    });

    $.validator.addMethod("validSalaryRange", function(value, element, params) {

        var isValid = true;
        var minSal = $('#tax_bracket_lower_bound').val();
        var maxSal = $('#tax_bracket_upper_bound').val();

        if(minSal != ""){
            minSal = parseFloat(minSal);
        }
        if(maxSal != ""){
            maxSal = parseFloat(maxSal);
        }

        if(minSal > maxSal && maxSal != "") {
            isValid = false;
        }
        return isValid;
    });
    
    if (!editable) {
        $('#payroll_configuration_epf_percentage').attr('disabled', true);
        $('#payroll_configuration_etf_percentage').attr('disabled', true);
    }

    $("#btnSave").click(function(e) {
        //if user clicks on Edit make all fields editable
        e.preventDefault();
        if($("#btnSave").attr('value') == edit) {

            $("#frmPayrollConfiguration .editable").each(function(){
                $(this).removeAttr("disabled");
            });
            $("#btnSave").attr('value', save);
            return;
        }

        if($("#btnSave").attr('value') == save) {
            if ($("#frmPayrollConfiguration").valid()) {
                $("#btnSave").val(lang_processing);
            }
            $("#frmPayrollConfiguration").submit();
        }
    });
});