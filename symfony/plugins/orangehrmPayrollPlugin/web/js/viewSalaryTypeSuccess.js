$(document).ready(function() {
    $('#btnCancel').click(function() {
        location.href = url_SalaryComponentList;
        validator.resetForm();
    });
    
    var validator = $('#frmSalaryType').validate({
        rules: {
            'salary_type[name]': {
                required: true,
                remote: {
                    url: url_CheckSalaryTypeNameNotExist,
                    type: 'post',
                    data: {
                        id: function() {
                            return $('#salary_type_id').val();
                        },
                        name: function() {
                            return $('#salary_type_name').val();
                        }
                    }
                }
            },
            'salary_type[monthly_basic]': {
                required: true,
                twoDecimals:true

            },
            'salary_type[other_allowance]': {
                twoDecimals:true

            },
            'salary_type[monthly_basic_tax]': {
                twoDecimals:true

            },
            'salary_type[monthly_nopay_leave]': {
                twoDecimals:true

            },
            'salary_type[monthly_epf_deduction]': {
                twoDecimals:true

            },
            'salary_type[monthly_etf_deduction]': {
                twoDecimals:true

            },
            'salary_type[jobtitle_id]': {
                required: true,

            },

        },
        messages: {
            'salary_type[name]': {
                required: lang_Required,
                remote: lang_AlreadyExists
            },
            'salary_type[monthly_basic]': {
                required: lang_Required,
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'salary_type[other_allowance]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'salary_type[monthly_basic_tax]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'salary_type[monthly_nopay_leave]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'salary_type[monthly_epf_deduction]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'salary_type[monthly_etf_deduction]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'salary_type[jobtitle_id]': {
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
    
    if (!editable) {
        $('#frmSalaryComponent :input').filter('[type!="button"]').attr('disabled', true);
    }
});