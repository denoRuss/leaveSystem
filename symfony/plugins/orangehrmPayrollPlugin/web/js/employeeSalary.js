$(document).ready(function () {
    $('#employee_salary_record_employee_name_empName').attr('disabled','disabled');

    $('#btnCancel').click(function() {
        location.href = url_employeeSalaryList;
        validator.resetForm();
    });

    var validator = $('#frmEmployeeSalaryRecord').validate({
        rules: {
            'employee_salary_record[salary_type_id]': {
                required: true,
            },
            'employee_salary_record[monthly_basic]': {
                required: true,
                twoDecimals:true

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
            'employee_salary_record[salary_type_id]': {
                required: lang_Required,
            },
            'employee_salary_record[monthly_basic]': {
                required: lang_Required,
                twoDecimals: lang_salaryShouldBeNumeric,
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
    
    $("#employee_salary_record_salary_type_id").change(function () {
        var salaryObject = salaryTypeList[$(this).val()];
        console.log(salaryObject);

        $("#employee_salary_record_monthly_basic").val(salaryObject['monthly_basic']);
        $("#employee_salary_record_other_allowance").val(salaryObject['other_allowance']);
        $("#employee_salary_record_monthly_basic_tax").val(salaryObject['monthly_basic_tax']);
        $("#employee_salary_record_monthly_nopay_leave").val(salaryObject['monthly_nopay_leave']);
        $("#employee_salary_record_monthly_epf_deduction").val(salaryObject['monthly_epf_deduction']);
        $("#employee_salary_record_monthly_etf_deduction").val(salaryObject['monthly_etf_deduction']);
    });
});