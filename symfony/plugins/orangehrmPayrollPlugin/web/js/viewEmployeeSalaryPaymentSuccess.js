$(document).ready(function () {

    $('#employee_salary_payment_employee_name_empName').attr('disabled','disabled');
    $('#employee_salary_payment_monthly_basic').attr('disabled','disabled');
    $('#employee_salary_payment_other_allowance').attr('disabled','disabled');
    $('#employee_salary_payment_monthly_basic_tax').attr('disabled','disabled');
    $('#employee_salary_payment_monthly_nopay_leave').attr('disabled','disabled');
    $('#employee_salary_payment_nopay_leave_count').attr('disabled','disabled');
    $('#employee_salary_payment_monthly_epf_deduction').attr('disabled','disabled');
    $('#employee_salary_payment_company_epf_deduction').attr('disabled','disabled');
    $('#employee_salary_payment_monthly_etf_deduction').attr('disabled','disabled');
    $('#employee_salary_payment_month').attr('disabled','disabled');
    $('#employee_salary_payment_year').attr('disabled','disabled');

    $('#btnCancel').click(function() {
        location.href = url_employeeSalaryList;
        validator.resetForm();
    });

    $('#btnSave').click(function (e) {
        e.preventDefault();

        if($(this).attr('value')=='Make Payment'){
            $('#employee_salary_payment_hdnAction').val('pay');
            $('#employee_salary_payment_monthly_basic').removeAttr('disabled');
            $('#employee_salary_payment_month').removeAttr('disabled');
            $('#employee_salary_payment_year').removeAttr('disabled');
        }
        else {
            $('#employee_salary_payment_hdnAction').val('adjust_pay');
            $('#employee_salary_payment_other_allowance').removeAttr('disabled');
            $('#employee_salary_payment_nopay_leave_count').removeAttr('disabled');
            $('#employee_salary_payment_monthly_basic').removeAttr('disabled');
            $('#employee_salary_payment_month').removeAttr('disabled');
            $('#employee_salary_payment_year').removeAttr('disabled');
        }
        $("#frmEmployeeSalaryPayment").submit()
    });

    $('#btnEdit').click(function(e) {
        e.preventDefault();

        if($(this).attr('value')=='Adjust Salary'){
            $(this).attr('value','Save');
            $("#btnSave").attr('value','Save & Make Payment');


            $('#employee_salary_payment_other_allowance').removeAttr('disabled');
            $('#employee_salary_payment_nopay_leave_count').removeAttr('disabled');
        }
        else {
            $('#employee_salary_payment_hdnAction').val('adjust');
            $('#employee_salary_payment_monthly_basic').removeAttr('disabled');
            $('#employee_salary_payment_month').removeAttr('disabled');
            $('#employee_salary_payment_year').removeAttr('disabled');
            $("#frmEmployeeSalaryPayment").submit();
        }

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
            'employee_salary_payment[company_epf_deduction]': {
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
                            return $("#employee_salary_payment_employee_name_empId").val()
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
            'employee_salary_payment[company_epf_deduction]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'employee_salary_payment[monthly_etf_deduction]': {
                twoDecimals: lang_salaryShouldBeNumeric,
            },
            'employee_salary_payment[year]': {
                remote: lang_alreadypaid
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


    $("#employee_salary_payment_month, #employee_salary_payment_year").change(function () {
        $.ajax({
            url:url_getNopayLeaveBalanceAjax,
            method:'POST',
            dataType:'JSON',
            data:{
                empNumber: empNumber,
                year:$("#employee_salary_payment_year").val(),
                month:$("#employee_salary_payment_month").val()
            },
            success: function (data) {
                console.log(data);
                $('#employee_salary_payment_monthly_nopay_leave').val(data.nopayLeaveDeduction);
            },
            error: function (error) {
                console.log(error);
            }
        })
    })

    $("#employee_salary_payment_nopay_leave_count").change(function () {

        var nopayLeaveDeduction = $(this).val()*$('#employee_salary_payment_monthly_basic').val()/30;
        $('#employee_salary_payment_monthly_nopay_leave').val(parseFloat(nopayLeaveDeduction).toFixed(2));
    })

});