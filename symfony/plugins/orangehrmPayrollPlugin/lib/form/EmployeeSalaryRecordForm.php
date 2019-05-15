<?php


class EmployeeSalaryRecordForm extends SalaryTypeForm
{
    public function configure() {
        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setNameFormat('employee_salary_record[%s]');
    }

    public function getFormWidgets(){
        $widgets =parent::getFormWidgets();
        unset($widgets['name']);

        $widgets['screen'] = new sfWidgetFormInputHidden(array(), array());
        $widgets['employee_name'] =  new ohrmWidgetEmployeeNameAutoFill(array('loadingMethod'=>'ajax'));
        $widgets['company_epf_deduction'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));
        return $widgets;
    }

    public function getFormValidators()
    {
        $validators = parent::getFormValidators();
        unset($validators['name']);

        $validators['screen'] = new sfValidatorPass(array("required" => false));
        $validators['employee_name'] = new ohrmValidatorEmployeeNameAutoFill();
        $validators['company_epf_deduction'] = new sfValidatorString(array('required' => false));

        return $validators;

    }

    public function getFormLabels(){
        $requiredLabelSuffix = ' <span class="required">*</span>';
        $labels = parent::getFormLabels();
        unset($labels['name']);
        $labels['employee_name'] = __('Employee Name').$requiredLabelSuffix;
        $labels['company_epf_deduction'] = __('Employer EPF Contribution');

        return $labels;
    }

    /**
     * @param $object
     */
    public function setEmployeeSalaryRecordObject($object,$employee,$screen)
    {
        if($object instanceof EmployeeSalaryRecord){
            $this->setDefault('id', $object->getId());
            $this->setDefault('monthly_basic', $object->getMonthlyBasic());
            $this->setDefault('other_allowance', $object->getOtherAllowance());
            $this->setDefault('monthly_basic_tax', $object->getMonthlyBasicTax());
            $this->setDefault('monthly_nopay_leave', $object->getMonthlyNopayLeave());
            $this->setDefault('monthly_epf_deduction', $object->getMonthlyEpfDeduction());
            $this->setDefault('company_epf_deduction', $object->getCompanyEpfDeduction());
            $this->setDefault('monthly_etf_deduction', $object->getMonthlyEtfDeduction());
        }

        $this->setDefault('employee_name', array('empName' => $employee->getFullName(), 'empId' => $employee->getEmpNumber()));
        $this->setDefault('screen',$screen);

        //TODO if payment date is going not 30, this has to be fixed, use diffrent from & to date
        $this->setDefault('monthly_nopay_leave',$this->getSalaryService()->calulateNopayLeaveDeduction($employee->getEmpNumber()));
    }

    protected function _setSalaryTypeeWidget() {

        $salaryTypeList = $this->getSalaryService()->getSalaryComponentList();
        $choices = array("" => "-- " . __('Select') . " --");

        foreach ($salaryTypeList as $job) {
            $choices[$job->getId()] = $job->getName();
        }

        $this->setWidget('salary_type_id', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->setValidator('salary_type_id', new sfValidatorChoice(array('choices' => array_keys($choices))));
    }

    /**
     * @return array|Doctrine_Record|EmployeeSalaryRecord|SalaryType
     * @throws Doctrine_Connection_Exception
     * @throws Doctrine_Record_Exception
     * @throws ServiceException
     */
    public function getObject()
    {
        if ($this->isBound()) {
            $object = new EmployeeSalaryRecord();

            $id = $this->getValue('id');
            if (!empty($id)) {
                $object = $this->getSalaryService()->getEmployeeSalaryRecord($id);
            }

            $object->setEmpNumber($this->getValue('employee_name')['empId']);
            $object->setMonthlyBasic($this->getValue('monthly_basic'));
            $object->setOtherAllowance($this->getValue('other_allowance')?$this->getValue('other_allowance'):null);
            $object->setMonthlyBasicTax($object->calculateMonthlyBasicTax($this->getValue('monthly_basic')));
            $object->setMonthlyNopayLeave($this->getValue('monthly_nopay_leave')?$this->getValue('monthly_nopay_leave'):null);
            $object->setMonthlyEpfDeduction($object->calculateMonthlyEpfDeduction($this->getValue('monthly_basic')));
            $object->setCompanyEpfDeduction($object->calculateCompanyEpfDeduction($this->getValue('monthly_basic')));
            $object->setMonthlyEtfDeduction($object->calculateMonthlyEtfDeduction($this->getValue('monthly_basic')));

            return $object;
        } else {
            throw new Exception('Data values are not bound yet');
        }
    }

    public function getJavaScripts() {
        $javascripts = array();
        return $javascripts;
    }

    public function getStylesheets()
    {
        return array();
    }

    public function getTaxBracketListAsJson() {

        $list = array();
        $taxBracketList = $this->getSalaryService()->getTaxBracketList();

        foreach ($taxBracketList as $taxBracket) {

            $list[] = array(
                'lower_bound'=> $taxBracket->getLowerBound(),
                'upper_bound' => $taxBracket->getUpperBound(),
                'percentage'=>$taxBracket->getPercentage(),


            );
        }
        return json_encode($list,JSON_NUMERIC_CHECK);
    }
}