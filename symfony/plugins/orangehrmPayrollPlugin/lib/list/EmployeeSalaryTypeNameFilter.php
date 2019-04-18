<?php


class EmployeeSalaryTypeNameFilter extends ohrmCellFilter
{

    public function filter($value)
    {
        $employee = $this->getEmployeeService()->getEmployee($value);
        $employeeSalaryRecord = $employee->getEmployeeSalaryRecord()->getFirst();
        if($employeeSalaryRecord instanceof EmployeeSalaryRecord){
            return $employeeSalaryRecord->getSalaryType()->getName();
        }
        else{
            return '_';
        }
    }

    public function getEmployeeService() {
        if(is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }
}