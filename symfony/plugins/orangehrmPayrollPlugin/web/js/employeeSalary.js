$(document).ready(function () {
    $('#employee_salary_record_employee_name_empName').attr('disabled','disabled');
    $('#employee_salary_record_monthly_basic_tax').attr('disabled','disabled');
    $('#employee_salary_record_monthly_epf_deduction').attr('disabled','disabled');
    $('#employee_salary_record_monthly_etf_deduction').attr('disabled','disabled');
    $('#employee_salary_record_monthly_basic').attr('disabled','disabled');
    $('#employee_salary_record_monthly_nopay_leave').attr('disabled','disabled');
    $('#employee_salary_record_other_allowance').attr('disabled','disabled');



    var validator = $('#frmEmployeeSalaryRecord').validate({
        rules: {
            'employee_salary_record[monthly_basic]': {
                required: true,
                twoDecimals:true,
                validTaxBracket:true
            },
            'employee_salary_record[other_allowance]': {
                twoDecimals:true

            },
            'employee_salary_record[monthly_basic_tax]': {
                twoDecimals:true

            },
            'employee_salary_record[monthly_nopay_leave]': {
                twoDecimals:true

            },
            'employee_salary_record[monthly_epf_deduction]': {
                twoDecimals:true

            },
            'employee_salary_record[monthly_etf_deduction]': {
                twoDecimals:true

            },
            'employee_salary_record[jobtitle_id]': {
                required: true,

            },

        },
        messages: {
            'employee_salary_record[monthly_basic]': {
                required: lang_Required,
                twoDecimals: lang_salaryShouldBeNumeric,
                validTaxBracket:lang_invalidTaxbracket
            },
            'employee_salary_record[other_allowance]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'employee_salary_record[monthly_basic_tax]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'employee_salary_record[monthly_nopay_leave]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'employee_salary_record[monthly_epf_deduction]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'employee_salary_record[monthly_etf_deduction]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'employee_salary_record[jobtitle_id]': {
                required: lang_Required,
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

    $.validator.addMethod("validTaxBracket", function(value, element, params) {

        value = parseFloat(value);
        var isValid = false;
        for(i=0;i<taxBracketList.length;i++){
            var taxBracket = taxBracketList[i];
            if(taxBracket.lower_bound<=value && value<=taxBracket.upper_bound){
                var tax= value*taxBracket.percentage/100;
                $("#employee_salary_record_monthly_basic_tax").val(tax);

                var epf = value*EPF_Percentage/100;
                $("#employee_salary_record_monthly_epf_deduction").val(epf);

                var etf = value*ETF_Percentage/100;
                $("#employee_salary_record_monthly_etf_deduction").val(etf);
                return true;
            }
        }
        $("#employee_salary_record_monthly_basic_tax").val('');
        $("#employee_salary_record_monthly_epf_deduction").val('');
        $("#employee_salary_record_monthly_etf_deduction").val('');
        return isValid;
    });

    $("#btnSave").click(function(e) {
        //if user clicks on Edit make all fields editable
        e.preventDefault();
        if($("#btnSave").attr('value') == edit) {

            $("#employee_salary_record_monthly_basic").removeAttr("disabled");
            $("#btnSave").attr('value', save);
            $("#btnCancel").removeClass('hide');
            return;
        }

        if($("#btnSave").attr('value') == save) {
            if ($("#frmEmployeeSalaryRecord").valid()) {
                $("#btnSave").val(lang_processing);
                $("#btnCancel").addClass('hide');
            }
            $("#frmEmployeeSalaryRecord").submit();
        }
    });

    $('#btnCancel').click(function() {
        location.href = url_employeeSalaryList;
        validator.resetForm();
    });
});


function calculatePayeeTax(taxBracketList,value) {

    for(i=0;i<taxBracketList.length;i++){
        var taxBracket = taxBracketList[i];
        if(taxBracket.lower_bound<=value && value<=taxBracket.upper_bound){
           var tax= value*taxBracket.percentage/100;
            $("#employee_salary_record_monthly_basic_tax").val(tax);
            $("#taxbracket_error").html('No Tax Bracket is defined for this range')
            return true;
        }
    }
}