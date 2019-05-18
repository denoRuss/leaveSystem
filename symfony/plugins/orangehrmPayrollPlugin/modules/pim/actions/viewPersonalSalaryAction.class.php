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
        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }


        $employee = $this->getEmployeeService()->getEmployee($empNumber);
        $employeeSalaryRecord = $employee->getEmployeeSalaryRecord()->getFirst();
        $form = new EmployeeSalaryRecordForm();
        $form->setEmployeeSalaryRecordObject($employeeSalaryRecord,$employee,EmployeeSalaryRecord::PIM_SALARY_SCREEN);

        $this->editable = true;
        $this->form = $form;
        $this->epfPercentage = $this->getSalaryConfigService()->getEpfPercentage();
        $this->companyepfPercentage = $this->getSalaryConfigService()->getCompanyEpfPercentage();
        $this->etfPercentage = $this->getSalaryConfigService()->getEtfPercentage();
        $this->salaryPermission = $this->getDataGroupPermissions('Payroll', $empNumber);
        $this->empNumber = $empNumber;

        $listData = $this->getSalaryService()->searchEmployeeSalaryHistory(array('emp_number'=>$empNumber));
        $this->setListComponent($listData);
    }


    public function setListComponent($listData)
    {
        $configurationFactory = new PIMSalaryHistoryListConfigurationFactory();


        $configurationFactory->setRuntimeDefinitions(array('title'=>'Salary Payment History'));

        ohrmListComponent::setActivePlugin('orangehrmPayrollPlugin');
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        ohrmListComponent::setListData($listData);
    }

    protected function IsActionAccessible($empNumber) {

        $isValidUser = true;

        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();

        $userRoleManager = $this->getContext()->getUserRoleManager();
        $accessible = $userRoleManager->isEntityAccessible('Employee', $empNumber);

        if ($empNumber != $loggedInEmpNum && (!$accessible)) {
            $isValidUser = false;
        }

        return $isValidUser;
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