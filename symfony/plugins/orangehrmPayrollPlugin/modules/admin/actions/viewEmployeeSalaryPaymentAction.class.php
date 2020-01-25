<?php


class viewEmployeeSalaryPaymentAction extends viewSalaryTypeListAction
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

        $filtes = $this->getUser()->getAttribute('emplist.filters', null, 'pim_module');
        $month = $filtes['month'];
        $year = $filtes['year'];
        $searchParam = array(
            'month'=>$month,
            'year'=>$year,
            'empNumber'=>$empNumber
        );

        $employeeSalaryRecord = $this->getSalaryService()->getSalaryDao()->getEmployeeMonthlySalaryRecord($searchParam);
        if(! $employeeSalaryRecord instanceof EmployeeMonthlySalaryRecord){
            $employeeSalaryRecord = $employee->getEmployeeSalaryRecord()->getFirst();
        }


        $form = new EmployeeSalaryPaymentForm(array(),$searchParam);
        $form->setEmployeeSalaryPaymentObject($employeeSalaryRecord,$employee,$year,$month);

        $this->editable = true;
        $this->form = $form;
        $this->title = 'Employee Salary Payment';
        $this->empNumber = $empNumber;

        $listData = $this->getSalaryService()->searchEmployeeSalaryHistory(array('emp_number'=>$empNumber));
        $this->setListComponent($listData);

    }

    public function getEmployeeService() {
        if(is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }

    public function setListComponent($listData)
    {
        $configurationFactory = new EmployeeSalaryHistoryListConfigurationFactory();


        $configurationFactory->setRuntimeDefinitions(array());

        ohrmListComponent::setActivePlugin('orangehrmPayrollPlugin');
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        ohrmListComponent::setListData($listData);
    }
}