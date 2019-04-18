<?php


class employeeSalaryAction extends viewSalaryTypeListAction
{
    public function execute($request)
    {
        /* For highlighting corresponding menu item */
        $request->setParameter('initialActionName', 'employeeSalaryList');

        if (!$this->getUser()->getAttribute('user')->isAdmin()) {
            $this->redirect('default/secure');
        }

        $empNumber = $request->getParameter('empNumber');
        $employee = $this->getEmployeeService()->getEmployee($empNumber);
        $employeeSalaryRecord = $employee->getEmployeeSalaryRecord()->getFirst();
        $form = new EmployeeSalaryRecordForm();
        $form->setEmployeeSalaryRecordObject($employeeSalaryRecord,$employee);

        $this->editable = true;
        $this->form = $form;
        $this->title = $employeeSalaryRecord instanceof EmployeeSalaryRecord? 'Edit Employee Salary Type' : 'Add Employee Salary Type';
    }

    public function getEmployeeService() {
        if(is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }
}