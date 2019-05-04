<?php


class PayrollConfigForm extends sfForm {
    
    protected $salaryComponentService;
    protected $jobTitleService;
    protected $salaryConfigService;
    protected $leaveTypeService;

    /**
     * @return DkSalaryService
     */
    public function getSalaryService() {
        if (!($this->salaryComponentService instanceof DkSalaryService)) {
            $this->salaryComponentService = new DkSalaryService();
        }
        return $this->salaryComponentService;
    }

    /**
     * @param DkSalaryService $salaryComponentService
     */
    public function setSalaryComponentService(DkSalaryService $salaryComponentService) {
        $this->salaryComponentService = $salaryComponentService;
    }

    /**
     * @return DkConfigService
     */
    public function getSalaryConfigService() {
        if (!($this->salaryConfigService instanceof DkConfigService)) {
            $this->salaryConfigService = new DkConfigService();
        }
        return $this->salaryConfigService;
    }

    /**
     * @return LeaveTypeService
     */
    protected function getLeaveTypeService() {
        if (!($this->leaveTypeService instanceof LeaveTypeService)) {
            $this->leaveTypeService = new LeaveTypeService();
        }
        return $this->leaveTypeService;
    }

    /**
     * Overriding the configure method 
     */
    public function configure() {
        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());
        $this->_setLeaveTypeWidget();
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setNameFormat('payroll_configuration[%s]');
    }

    private function _setLeaveTypeWidget() {

        $leaveTypeList = $this->getLeaveTypeService()->getLeaveTypeList();
        $leaveTypeChoices = array('-1' => '--' . __('Select') . '--');
        foreach ($leaveTypeList as $leaveType) {
            $leaveTypeChoices[$leaveType->getId()] = $leaveType->getName();
        }

        $this->setWidget('leave_type_id', new sfWidgetFormChoice(array('choices' => $leaveTypeChoices)));
        $this->setValidator('leave_type_id', new sfValidatorChoice(array('choices' => array_keys($leaveTypeChoices))));
    }

    /**
     *
     */
    public function setObject() {

        
        $this->setDefault('epf_percentage', $this->getSalaryConfigService()->getEpfPercentage());
        $this->setDefault('company_epf_percentage', $this->getSalaryConfigService()->getCompanyEpfPercentage());
        $this->setDefault('etf_percentage', $this->getSalaryConfigService()->getEtfPercentage());
        $this->setDefault('leave_type_id', $this->getSalaryConfigService()->getNopayLeaveTypeId());
        $this->setDefault('nopay_leave_deduction', $this->getSalaryConfigService()->getNopayLeaveDeduction());

    }

    /**
     * @throws ServiceException
     */
    public function save() {
        if ($this->isBound()) {
            try{
                //save efp percentage
                $this->getSalaryConfigService()->setEpfPercentage($this->getValue('epf_percentage')?$this->getValue('epf_percentage'):0);
                $this->getSalaryConfigService()->setCompanyEpfPercentage($this->getValue('company_epf_percentage')?$this->getValue('company_epf_percentage'):0);
                $this->getSalaryConfigService()->setEtfPercentage($this->getValue('etf_percentage')?$this->getValue('etf_percentage'):0);
                $this->getSalaryConfigService()->setNopayLeaveTypeId($this->getValue('leave_type_id')?$this->getValue('leave_type_id'):-1);
                $this->getSalaryConfigService()->setNopayLeaveDeduction($this->getValue('nopay_leave_deduction')?$this->getValue('nopay_leave_deduction'):0);
            }
            catch (Exception $e){
                throw new ServiceException($e->getMessage());
            }

        } else {
            throw new Exception('Data values are not bound yet');
        }
    }


    /**
     *
     * @return array 
     */
    protected function getFormWidgets() {
        $widgets = array();

        $widgets['epf_percentage'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));
        $widgets['company_epf_percentage'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));
        $widgets['etf_percentage'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));
        $widgets['nopay_leave_deduction'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));


        return $widgets;
    }

    /**
     *
     * @return array 
     */
    protected function getFormValidators() {
        $validators = array();

        $validators['epf_percentage'] = new sfValidatorString(array('required' => true));
        $validators['company_epf_percentage'] = new sfValidatorString(array('required' => true));
        $validators['etf_percentage'] = new sfValidatorString(array('required' => true));
        $validators['nopay_leave_deduction'] = new sfValidatorString(array('required' => false));


        return $validators;
    }

    /**
     *
     * @return array 
     */
    protected function getFormLabels() {
        $requiredLabelSuffix = ' <span class="required">*</span>';

        $labels = array(
            'epf_percentage' => __('Employee EPF (%)') . $requiredLabelSuffix,
            'company_epf_percentage' => __('Employer EPF (%)') . $requiredLabelSuffix,
            'etf_percentage' => __('ETF (%)') . $requiredLabelSuffix,
            'leave_type_id' => __('Leave Type') . $requiredLabelSuffix,
            'nopay_leave_deduction' => __('Deduction per Leave'),
        );

        return $labels;
    }



}
