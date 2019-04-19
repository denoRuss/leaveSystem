$(document).ready(function () {

    $('#employee_salary_payment_employee_name_empName').attr('disabled','disabled');

    $('#btnCancel').click(function() {
        location.href = url_employeeSalaryList;
        validator.resetForm();
    });



    var validator = $('#frmEmployeeSalaryPayment').validate({
        rules: {
            'employee_salary_payment[monthly_basic]': {
                required: true,
                twoDecimals:true

            },
            'employee_salary_payment[other_allowance]': {
                twoDecimals:true

            },
            'employee_salary_payment[monthly_basic_tax]': {
                twoDecimals:true

            },
            'employee_salary_payment[monthly_nopay_leave]': {
                twoDecimals:true

            },
            'employee_salary_payment[monthly_epf_deduction]': {
                twoDecimals:true

            },
            'employee_salary_payment[monthly_etf_deduction]': {
                twoDecimals:true

            },
            // 'employee_salary_payment[year]': {
            //     remote: {
            //         url: url_CheckEmployeeSalaryPaymentExist,
            //         type: 'post',
            //         data: {
            //             year: function() {
            //                 return $('#employee_salary_payment_year').val();
            //             },
            //             month: function() {
            //                 return $('#employee_salary_payment_month').val();
            //             },
            //             empNumber: function() {
            //                 return 1;
            //             }
            //         }
            //     }
            // },
            'employee_salary_payment[month]': {
                remote: {
                    url: url_CheckEmployeeSalaryPaymentExist,
                    type: 'post',
                    data: {
                        year: function() {
                            return $('#employee_salary_payment_year').val();
                        },
                        month: function() {
                            return $('#employee_salary_payment_month').val();
                        },
                        empNumber: function() {
                            return 1;
                        }
                    }
                }
            },

        },
        messages: {

            'employee_salary_payment[monthly_basic]': {
                required: lang_Required,
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'employee_salary_payment[other_allowance]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'employee_salary_payment[monthly_basic_tax]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'employee_salary_payment[monthly_nopay_leave]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'employee_salary_payment[monthly_epf_deduction]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'employee_salary_payment[monthly_etf_deduction]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'employee_salary_payment[year]': {
                remote: lang_alreadypaid
                // remote: lang_alreadypaid+' '+$('#employee_salary_payment_year').val()+' '+ $("#employee_salary_payment_month option:selected").text()
            },
            'employee_salary_payment[month]': {
                remote:  lang_alreadypaid
            },

        },
        errorPlacement: function(error, element) {
            if (element.is('#employee_salary_payment_year') || element.is('#employee_salary_payment_month')) {
                error.insertAfter($('#payemnt_error'));
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

});