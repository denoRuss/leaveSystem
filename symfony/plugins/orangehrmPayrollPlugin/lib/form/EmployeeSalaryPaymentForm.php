<?php


class EmployeeSalaryPaymentForm extends EmployeeSalaryRecordForm
{
    protected $employeeService;
    public function configure() {
        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());
        $this->_setYearAndMonthWidget();
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setNameFormat('employee_salary_payment[%s]');
    }

    protected function _setYearAndMonthWidget() {

        $yearChoices = $this->getYearList();
        $monthCoices = $this->getMonthList();

        $this->setWidget('year', new sfWidgetFormChoice(array('choices' => $yearChoices)));
        $this->setValidator('year', new sfValidatorChoice(array('choices' => array_keys($yearChoices))));

        $this->setWidget('month', new sfWidgetFormChoice(array('choices' => $monthCoices)));
        $this->setValidator('month', new sfValidatorChoice(array('choices' => array_keys($monthCoices))));
    }

    public function getYearList()
    {
        $years = array();
        $currentYear = sfConfig::get("app_show_years_from");
        for ($currentYear; $currentYear <= (date("Y")); $currentYear++) {
            $years [$currentYear] = $currentYear;
        }
        return $years;
    }

    public function getMonthList($year = null)
    {

        if (!$year) {
            $year = $this->getOption('year');
        }

        $monthList = array();

        if (date("Y") != $year && strlen($year) > 0) {

            for ($i = 1; $i <= 12; $i++) {
                $monthList [date("n", strtotime("2012-" . $i . "-1"))] = date("F", strtotime("2012-" . $i . "-1"));
            }
        } else {
            for ($i = 1; $i <= date("n"); $i++) {
                $monthList [date("n", strtotime("2012-" . $i . "-1"))] = date("F", strtotime("2012-" . $i . "-1"));
            }
        }
        return $monthList;
    }

    public function getFormLabels(){
        $requiredLabelSuffix = ' <span class="required">*</span>';
        $labels = parent::getFormLabels();

        $labels['year'] = __('Year').$requiredLabelSuffix;
        $labels['month'] = __('Month').$requiredLabelSuffix;

        return $labels;
    }

    public function getObject()
    {
        if ($this->isBound()) {
            $object = new EmployeeSalaryHistory();

            $employee = $this->getEmployeeService()->getEmployee($this->getValue('employee_name')['empId']);
            $employeeSalaryRecord = $employee->getEmployeeSalaryRecord()->getFirst();

            /**
             * @var EmployeeSalaryRecord $employeeSalaryRecord
             */
            $object->setEmpNumber($this->getValue('employee_name')['empId']);
            $object->setMonthlyBasic($employeeSalaryRecord->getMonthlyBasic());
            $object->setOtherAllowance($employeeSalaryRecord->getOtherAllowance()?$employeeSalaryRecord->getOtherAllowance():null);
            $object->setMonthlyBasicTax($employeeSalaryRecord->getMonthlyBasicTax()?$employeeSalaryRecord->getMonthlyBasicTax():null);
            $object->setMonthlyNopayLeave($employeeSalaryRecord->getMonthlyNopayLeave()?$employeeSalaryRecord->getMonthlyNopayLeave():null);
            $object->setMonthlyEpfDeduction($employeeSalaryRecord->getMonthlyEpfDeduction()?$employeeSalaryRecord->getMonthlyEpfDeduction():null);
            $object->setMonthlyEtfDeduction($employeeSalaryRecord->getMonthlyEtfDeduction()?$employeeSalaryRecord->getMonthlyEtfDeduction():null);
            $object->setTotalEarning($object->calculateTotalEarnings());
            $object->setTotalDeduction($object->calculateTotalDeduction());
            $object->setTotalNetsalary($object->calculateTotalNetsalary());
            $object->setMonth($this->getValue('month'));
            $object->setYear($this->getValue('year'));

            return $object;
        } else {
            throw new Exception('Data values are not bound yet');
        }
    }

    public function setEmployeeSalaryPaymentObject($object,$employee){

        if($object instanceof EmployeeSalaryRecord){
            $this->setDefault('monthly_basic', $object->getMonthlyBasic());
            $this->setDefault('other_allowance', $object->getOtherAllowance());
            $this->setDefault('monthly_basic_tax', $object->getMonthlyBasicTax());
            $this->setDefault('monthly_nopay_leave', $object->getMonthlyNopayLeave());
            $this->setDefault('monthly_epf_deduction', $object->getMonthlyEpfDeduction());
            $this->setDefault('monthly_etf_deduction', $object->getMonthlyEtfDeduction());
        }

        $this->setDefault('employee_name', array('empName' => $employee->getFullName(), 'empId' => $employee->getEmpNumber()));
    }

    public function getEmployeeService() {
        if(is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }
}