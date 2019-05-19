<?php


class EmployeeSalaryPaymentForm extends EmployeeSalaryRecordForm
{
    const ADJUST_SALARY = 'adjust';
    const MAKE_PAYMENT = 'pay';
    const ADJUST_AND_MAKE_PAYMENT = 'adjust_pay';

    protected $employeeService;
    public function configure() {
        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());
        $this->_setActionWidget();
        $this->_setNopayLeaveCountWidget();
        $this->_setYearAndMonthWidget();
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setNameFormat('employee_salary_payment[%s]');
    }


    protected function _setNopayLeaveCountWidget(){
        $this->setWidget('nopay_leave_count',  new sfWidgetFormInputText(array(), array('class' => 'formInputText')));
        $this->setValidator('nopay_leave_count', new sfValidatorString(array('required' => false)));
    }
    protected function _setActionWidget(){
        $this->setWidget('hdnAction', new sfWidgetFormInputHidden(array(), array()));
        $this->setValidator('hdnAction', new sfValidatorPass(array("required" => true)));
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
        $labels['nopay_leave_count'] = __('Num of Nopay Leave');

        return $labels;
    }

    public function getObject()
    {
        if ($this->isBound()) {
            $object = new EmployeeSalaryHistory();

            $from = date('Y-m-d',strtotime($this->getValue('year').'-'.$this->getValue('month').'-01'));
            $to = date('Y-m-t',strtotime($from));

            //check whether monthly salary record is existing
            $searchParams = array(
                'month'=>$this->getValue('month'),
                'year'=>$this->getValue('year'),
                'emp_number'=>$this->getValue('employee_name')['empId']
            );
            $existingMonthlySalaryRecord = $this->getSalaryService()->getEmployeeMonthlySalaryRecord($searchParams);
            $nopayLeaveCount = 0;
            if($existingMonthlySalaryRecord instanceof EmployeeMonthlySalaryRecord){
                $employeeSalaryRecord = $existingMonthlySalaryRecord;
                $nopayLeaveCount = $existingMonthlySalaryRecord->getNopayLeaveCount();
            }
            else{
                $employee = $this->getEmployeeService()->getEmployee($this->getValue('employee_name')['empId']);
                $employeeSalaryRecord = $employee->getEmployeeSalaryRecord()->getFirst();
            }


            /**
             * @var EmployeeSalaryRecord $employeeSalaryRecord
             */
            $object->setEmpNumber($this->getValue('employee_name')['empId']);
            $object->setMonthlyBasic($employeeSalaryRecord->getMonthlyBasic());
            $object->setOtherAllowance($employeeSalaryRecord->getOtherAllowance()?$employeeSalaryRecord->getOtherAllowance():null);
            $object->setMonthlyBasicTax($employeeSalaryRecord->getMonthlyBasicTax()?$employeeSalaryRecord->getMonthlyBasicTax():null);

            //TODO this is still pending
//            $object->setMonthlyNopayLeave($this->getSalaryService()->calulateNopayLeaveDeduction($employee->getEmpNumber(),$from,$to));
            $object->setMonthlyNopayLeave($this->getSalaryService()->calculateNopayLeaveDedcutionBasedOnSalary($employeeSalaryRecord->getMonthlyBasic(),$nopayLeaveCount));


            $object->setMonthlyEpfDeduction($employeeSalaryRecord->getMonthlyEpfDeduction()?$employeeSalaryRecord->getMonthlyEpfDeduction():null);
            $object->setCompanyEpfDeduction($employeeSalaryRecord->getCompanyEpfDeduction()?$employeeSalaryRecord->getCompanyEpfDeduction():null);
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

    public function getModifiedEmployeeSalaryHistory()
    {
        if ($this->isBound()) {
            $object = new EmployeeSalaryHistory();

            $employee = $this->getEmployeeService()->getEmployee($this->getValue('employee_name')['empId']);
            $employeeSalaryRecord = $employee->getEmployeeSalaryRecord()->getFirst();
            $from = date('Y-m-d',strtotime($this->getValue('year').'-'.$this->getValue('month').'-01'));
            $to = date('Y-m-t',strtotime($from));


            /**
             * @var EmployeeSalaryRecord $employeeSalaryRecord
             */
            $object->setEmpNumber($this->getValue('employee_name')['empId']);
            $object->setMonthlyBasic($employeeSalaryRecord->getMonthlyBasic());
            $object->setOtherAllowance($this->getValue('other_allowance')?$this->getValue('other_allowance'):null);
            $object->setMonthlyBasicTax($employeeSalaryRecord->getMonthlyBasicTax()?$employeeSalaryRecord->getMonthlyBasicTax():null);

            //TODO this is still pending
            $object->setMonthlyNopayLeave($this->getSalaryService()->calculateNopayLeaveDedcutionBasedOnSalary($employeeSalaryRecord->getMonthlyBasic(),
                $this->getValue('nopay_leave_count')));


            $object->setMonthlyEpfDeduction($employeeSalaryRecord->getMonthlyEpfDeduction()?$employeeSalaryRecord->getMonthlyEpfDeduction():null);
            $object->setCompanyEpfDeduction($employeeSalaryRecord->getCompanyEpfDeduction()?$employeeSalaryRecord->getCompanyEpfDeduction():null);
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

    public function getEmployeeMonthlySalaryRecord(){
        if ($this->isBound()) {


            $employee = $this->getEmployeeService()->getEmployee($this->getValue('employee_name')['empId']);
            $employeeSalaryRecord = $employee->getEmployeeSalaryRecord()->getFirst();
            $from = date('Y-m-d',strtotime($this->getValue('year').'-'.$this->getValue('month').'-01'));
            $to = date('Y-m-t',strtotime($from));

            $searchParams = array(
                'month'=>$this->getValue('month'),
                'year'=>$this->getValue('year'),
                'emp_number'=>$this->getValue('employee_name')['empId']);
            $existingEmployeeMonthlySalaryRecord = $this->getSalaryService()->getEmployeeMonthlySalaryRecord($searchParams);

            if($existingEmployeeMonthlySalaryRecord instanceof EmployeeMonthlySalaryRecord){
                $object = $existingEmployeeMonthlySalaryRecord;
            }
            else{

                /**
                 * @var EmployeeMonthlySalaryRecord $object
                 */
                $object = new EmployeeMonthlySalaryRecord();
                $object->setEmpNumber($this->getValue('employee_name')['empId']);
                $object->setMonthlyBasic($employeeSalaryRecord->getMonthlyBasic());
                $object->setMonthlyBasicTax($employeeSalaryRecord->getMonthlyBasicTax()?$employeeSalaryRecord->getMonthlyBasicTax():null);
                $object->setMonthlyEpfDeduction($employeeSalaryRecord->getMonthlyEpfDeduction()?$employeeSalaryRecord->getMonthlyEpfDeduction():null);
                $object->setCompanyEpfDeduction($employeeSalaryRecord->getCompanyEpfDeduction()?$employeeSalaryRecord->getCompanyEpfDeduction():null);
                $object->setMonthlyEtfDeduction($employeeSalaryRecord->getMonthlyEtfDeduction()?$employeeSalaryRecord->getMonthlyEtfDeduction():null);

            }


            $object->setOtherAllowance($this->getValue('other_allowance')?$this->getValue('other_allowance'):null);

            //TODO this is still pending
            $object->setNopayLeaveCount($this->getValue('nopay_leave_count')?$this->getValue('nopay_leave_count'):null);
            $object->setMonthlyNopayLeave($this->getSalaryService()->calculateNopayLeaveDedcutionBasedOnSalary($employeeSalaryRecord->getMonthlyBasic(),
                $this->getValue('nopay_leave_count')));
            //$object->setMonthlyNopayLeave($this->getSalaryService()->calulateNopayLeaveDeduction($employee->getEmpNumber(),$from,$to));


            $object->setMonth($this->getValue('month'));
            $object->setYear($this->getValue('year'));

            return $object;
        } else {
            throw new Exception('Data values are not bound yet');
        }
    }

    public function setEmployeeSalaryPaymentObject($object,$employee,$year,$month){

        if($object instanceof EmployeeSalaryRecord || $object instanceof EmployeeMonthlySalaryRecord){
            $this->setDefault('monthly_basic', $object->getMonthlyBasic());
            $this->setDefault('other_allowance', $object->getOtherAllowance());
            $this->setDefault('monthly_basic_tax', $object->getMonthlyBasicTax());
            $this->setDefault('monthly_epf_deduction', $object->getMonthlyEpfDeduction());
            $this->setDefault('company_epf_deduction', $object->getCompanyEpfDeduction());
            $this->setDefault('monthly_etf_deduction', $object->getMonthlyEtfDeduction());
        }

        $this->setDefault('employee_name', array('empName' => $employee->getFullName(), 'empId' => $employee->getEmpNumber()));

        if($object instanceof EmployeeMonthlySalaryRecord){
            $this->setDefault('year',$object->getYear());
            $this->setDefault('month',$object->getMonth());
            $this->setDefault('monthly_nopay_leave',$object->getMonthlyNopayLeave());
            $this->setDefault('nopay_leave_count',$object->getNopayLeaveCount());
        }
        else{
            $this->setDefault('year',$year);
            $this->setDefault('month',$month);

            //TODO this is still pending
            $this->setDefault('monthly_nopay_leave', $this->getSalaryService()->calulateNopayLeaveDeduction($employee->getEmpNumber()));
        }

        $this->setDefault('hdnAction',self::ADJUST_SALARY);

    }

    public function getEmployeeService() {
        if(is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }
}