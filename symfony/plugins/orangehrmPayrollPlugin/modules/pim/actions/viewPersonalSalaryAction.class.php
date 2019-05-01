<?php


class viewPersonalSalaryAction extends basePimAction
{
    protected $salaryConfigService;
    protected $salaryService;

    public function execute($request)
    {
        /* For highlighting corresponding menu item */
//        $request->setParameter('initialActionName', 'viewEmployeeList');



        $empNumber = $request->getParameter('empNumber');
        $employee = $this->getEmployeeService()->getEmployee($empNumber);
        $employeeSalaryRecord = $employee->getEmployeeSalaryRecord()->getFirst();
        $form = new EmployeeSalaryRecordForm();
        $form->setEmployeeSalaryRecordObject($employeeSalaryRecord,$employee);

        $this->editable = true;
        $this->form = $form;
        $this->epfPercentage = $this->getSalaryConfigService()->getEpfPercentage();
        $this->etfPercentage = $this->getSalaryConfigService()->getEtfPercentage();
        $this->salaryPermission = $this->getDataGroupPermissions('Payroll', $empNumber);
        $this->empNumber = $empNumber;
    }


    public function getSalaryConfigService() {
        if (!($this->salaryConfigService instanceof DkConfigService)) {
            $this->salaryConfigService = new DkConfigService();
        }
        return $this->salaryConfigService;
    }

    public function getSalaryService() {
        if (!($this->salaryService instanceof DkSalaryService)) {
            $this->salaryService = new DkSalaryService();
        }
        return $this->salaryService;
    }


}