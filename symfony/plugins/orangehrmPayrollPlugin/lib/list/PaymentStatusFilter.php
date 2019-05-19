<?php


class PaymentStatusFilter extends ohrmCellFilter
{
    protected $employeeService;
    public function filter($value)
    {
        return is_null($value) ?'Salary Details':'Paid';
    }

    public function getEmployeeService() {
        if(is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }
}