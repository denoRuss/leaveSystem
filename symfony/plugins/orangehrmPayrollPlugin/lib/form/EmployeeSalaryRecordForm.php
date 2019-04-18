<?php


class EmployeeSalaryRecordForm extends SalaryTypeForm
{
    public function configure() {
        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->_setSalaryTypeeWidget();
        $this->getWidgetSchema()->setNameFormat('employee_salary_record[%s]');
    }

    public function getFormWidgets(){
        $widgets =parent::getFormWidgets();
        unset($widgets['name']);

        $widgets['employee_name'] =  new ohrmWidgetEmployeeNameAutoFill(array('loadingMethod'=>'ajax'));
        return $widgets;
    }

    public function getFormValidators()
    {
        $validators = parent::getFormValidators();
        unset($validators['name']);
        $validators['employee_name'] = new ohrmValidatorEmployeeNameAutoFill();

        return $validators;

    }

    public function getFormLabels(){
        $requiredLabelSuffix = ' <span class="required">*</span>';
        $labels = parent::getFormLabels();
        unset($labels['name']);
        $labels['employee_name'] = __('Employee Name').$requiredLabelSuffix;
        $labels['employee_name'] = __('Employee Name').$requiredLabelSuffix;

        return $labels;
    }

    /**
     * @param $object
     */
    public function setEmployeeSalaryRecordObject($object,$employee)
    {
        if($object instanceof EmployeeSalaryRecord){
            $this->setDefault('id', $object->getId());
            $this->setDefault('monthly_basic', $object->getMonthlyBasic());
            $this->setDefault('other_allowance', $object->getOtherAllowance());
            $this->setDefault('monthly_basic_tax', $object->getMonthlyBasicTax());
            $this->setDefault('monthly_nopay_leave', $object->getMonthlyNopayLeave());
            $this->setDefault('monthly_epf_deduction', $object->getMonthlyEpfDeduction());
            $this->setDefault('monthly_etf_deduction', $object->getMonthlyEtfDeduction());
        }

        $this->setDefault('employee_name', array('empName' => $employee->getFullName(), 'empId' => $employee->getEmpNumber()));
    }

    protected function _setSalaryTypeeWidget() {

        $salaryTypeList = $this->getSalaryService()->getSalaryComponentList();
        $choices = array();

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
            $object->setSalaryTypeId($this->getValue('salary_type_id'));
            $object->setMonthlyBasic($this->getValue('monthly_basic'));
            $object->setOtherAllowance($this->getValue('other_allowance')?$this->getValue('other_allowance'):null);
            $object->setMonthlyBasicTax($this->getValue('monthly_basic_tax')?$this->getValue('monthly_basic_tax'):null);
            $object->setMonthlyNopayLeave($this->getValue('monthly_nopay_leave')?$this->getValue('monthly_nopay_leave'):null);
            $object->setMonthlyEpfDeduction($this->getValue('monthly_epf_deduction')?$this->getValue('monthly_epf_deduction'):null);
            $object->setMonthlyEtfDeduction($this->getValue('monthly_etf_deduction')?$this->getValue('monthly_etf_deduction'):null);

            return $object;
        } else {
            throw new Exception('Data values are not bound yet');
        }
    }

    public function getJavaScripts() {
        $javascripts = array();
        return $javascripts;
    }
}