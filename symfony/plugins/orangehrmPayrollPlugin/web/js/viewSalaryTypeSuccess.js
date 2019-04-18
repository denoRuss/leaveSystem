$(document).ready(function() {
    $('#btnCancel').click(function() {
        location.href = url_SalaryComponentList;
    });
    
    $('#frmSalaryComponent').validate({
        rules: {
            'salary_component[name]': {
                required: true,
                maxlength: 60,
                remote: {
                    url: url_CheckSalaryComponentNameNotExist,
                    type: 'post',
                    data: {
                        id: function() {
                            return $('#salary_component_id').val();
                        },
                        name: function() {
                            return $('#salary_component_name').val();
                        }
                    }
                }
            },
            'salary_component[type]': {
                required: true
            },
            'salary_component[cost_to_company]': {
                required: true
            },
            'salary_component[value_type][]': {
                required: true
            }
        },
        messages: {
            'salary_component[name]': {
                required: lang_Required,
                maxlength: lang_LengthExceeded_60,
                remote: lang_AlreadyExists
            },
            'salary_component[type]': {
                required: lang_Required
            },
            'salary_component[cost_to_company]': {
                required: lang_Required
            },
            'salary_component[value_type][]': {
                required: lang_Required
            }
        },
        errorPlacement: function(error, element) {
            if (element.is('#salary_component_value_type_1')) {
                error.insertAfter($('#salary_component_value_type_2').parent().parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
    
    if (!editable) {
        $('#frmSalaryComponent :input').filter('[type!="button"]').attr('disabled', true);
    }
});