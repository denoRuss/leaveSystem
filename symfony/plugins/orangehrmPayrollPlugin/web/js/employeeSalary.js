$(document).ready(function () {
    $('#employee_salary_record_employee_name_empName').attr('disabled','disabled');

    $('#btnCancel').click(function() {
        location.href = url_employeeSalaryList;
    });
    console.log('employe salay js');
});