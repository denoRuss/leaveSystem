$(document).ready(function() {
    $('#btnCancel').click(function() {
        location.href = url_TaxBracketList;
        //validator.resetForm();
    });
    
    var validator = $('#frmTaxBracket').validate({
        rules: {
            'tax_bracket[lower_bound]': {
                required: true,
                twoDecimals:true,
                remote: {
                    url: url_TaxBracketBoundsNotExist,
                    type: 'post',
                    data: {
                        lower_bound: function() {
                            return $('#tax_bracket_lower_bound').val();
                        },
                        upper_bound: function() {
                            return $('#tax_bracket_upper_bound').val();
                        },
                        id: function() {
                            return $('#tax_bracket_id').val();
                        }

                    }
                }

            },
            'tax_bracket[upper_bound]': {
                required: true,
                twoDecimals:true,
                validSalaryRange: true,
                remote: {
                    url: url_TaxBracketBoundsNotExist,
                    type: 'post',
                    data: {
                        lower_bound: function() {
                            return $('#tax_bracket_lower_bound').val();
                        },
                        upper_bound: function() {
                            return $('#tax_bracket_upper_bound').val();
                        },
                        id: function() {
                            return $('#tax_bracket_id').val();
                        }
                    }
                }

            },
            'tax_bracket[percentage]': {
                required: true,
                twoDecimals:true

            },


        },
        messages: {

            'tax_bracket[lower_bound]': {
                required: lang_Required,
                twoDecimals: lang_salaryShouldBeNumeric,
                remote: lang_overlappingTaxBracket
            },
            'tax_bracket[upper_bound]': {
                required: lang_Required,
                twoDecimals: lang_salaryShouldBeNumeric,
                validSalaryRange: lang_validSalaryRange,
                remote: lang_overlappingTaxBracket

            },
            'tax_bracket[percentage]': {
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
        $('#frmTaxBracket :input').filter('[type!="button"]').attr('disabled', true);
    }
});