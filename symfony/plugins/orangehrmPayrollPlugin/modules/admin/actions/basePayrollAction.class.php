<?php


class basePayrollAction extends sfAction
{

    protected $salaryConfigService;
    protected $salaryService;

    function execute($request)
    {

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


    public function setSalaryConfigService(DkConfigService $service) {
        $this->salaryConfigService = $service;
    }

    public function getSalaryService() {
        if (!($this->salaryService instanceof DkSalaryService)) {
            $this->salaryService = new DkSalaryService();
        }
        return $this->salaryService;
    }


    public function setSalaryService(DkSalaryService $service) {
        $this->salaryService = $service;
    }
}