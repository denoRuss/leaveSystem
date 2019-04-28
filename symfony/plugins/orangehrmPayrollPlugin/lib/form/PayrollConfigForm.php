<?php


class PayrollConfigForm extends sfForm {
    
    protected $salaryComponentService;
    protected $jobTitleService;
    protected $salaryConfigService;


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
     * @return JobTitleService
     */
    public function getJobTitleService() {
        if (is_null($this->jobTitleService)) {
            $this->jobTitleService = new JobTitleService();
            $this->jobTitleService->setJobTitleDao(new JobTitleDao());
        }
        return $this->jobTitleService;
    }


    /**
     * Overriding the configure method 
     */
    public function configure() {
        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setNameFormat('payroll_configuration[%s]');
    }

    private function _setJobTitleWidget() {

        $jobTitleList = $this->getJobTitleService()->getJobTitleList();
        $choices = array();

        foreach ($jobTitleList as $job) {
            $choices[$job->getId()] = $job->getJobTitleName();
        }

        $this->setWidget('jobtitle_id', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->setValidator('jobtitle_id', new sfValidatorChoice(array('choices' => array_keys($choices))));
    }

    /**
     *
     */
    public function setObject() {

        
        $this->setDefault('epf_percentage', $this->getSalaryConfigService()->getEpfPercentage());
        $this->setDefault('etf_percentage', $this->getSalaryConfigService()->getEtfPercentage());
//        $this->setDefault('other_allowance', $object->getOtherAllowance());
//        $this->setDefault('monthly_basic_tax', $object->getMonthlyBasicTax());
//        $this->setDefault('monthly_nopay_leave', $object->getMonthlyNopayLeave());
//        $this->setDefault('monthly_epf_deduction', $object->getMonthlyEpfDeduction());
//        $this->setDefault('monthly_etf_deduction', $object->getMonthlyEtfDeduction());
//        $this->setDefault('jobtitle_id', $object->getJobtitleId());
    }

    /**
     * @throws ServiceException
     */
    public function save() {
        if ($this->isBound()) {
            try{
                //save efp percentage
                $this->getSalaryConfigService()->setEpfPercentage($this->getValue('epf_percentage')?$this->getValue('epf_percentage'):0);
                $this->getSalaryConfigService()->setEtfPercentage($this->getValue('etf_percentage')?$this->getValue('etf_percentage'):0);
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
        $widgets['etf_percentage'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));


        return $widgets;
    }

    /**
     *
     * @return array 
     */
    protected function getFormValidators() {
        $validators = array();

        $validators['epf_percentage'] = new sfValidatorString(array('required' => true));
        $validators['etf_percentage'] = new sfValidatorString(array('required' => true));


        return $validators;
    }

    /**
     *
     * @return array 
     */
    protected function getFormLabels() {
        $requiredLabelSuffix = ' <span class="required">*</span>';

        $labels = array(
            'epf_percentage' => __('EPF Percentage') . $requiredLabelSuffix,
            'etf_percentage' => __('ETF Percentage') . $requiredLabelSuffix,
        );

        return $labels;
    }

    public function getSalaryTypeListAsJson() {

        $list = array();
        $salaryTypeList = $this->getSalaryService()->getSalaryComponentList();

        foreach ($salaryTypeList as $salaryType) {

                $list[$salaryType->getId()] = array(
                    'name'=> $salaryType->getName(),
                    'monthly_basic' => $salaryType->getMonthlyBasic(),
                    'other_allowance'=>$salaryType->getOtherAllowance(),
                    'monthly_basic_tax'=>$salaryType->getMonthlyBasicTax(),
                    'monthly_nopay_leave'=>$salaryType->getMonthlyNopayLeave(),
                    'monthly_epf_deduction'=>$salaryType->getMonthlyEpfDeduction(),
                    'monthly_etf_deduction'=>$salaryType->getMonthlyEtfDeduction(),

                );
        }
        return json_encode($list);
    }

}
