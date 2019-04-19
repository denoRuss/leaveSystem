<?php


class SalaryTypeForm extends sfForm {
    
    protected $salaryComponentService;
    protected $jobTitleService;


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
        $this->_setJobTitleWidget();
        $this->getWidgetSchema()->setNameFormat('salary_type[%s]');
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
     * @param SalaryComponent $object 
     */
    public function setObject(SalaryType $object) {

        
        $this->setDefault('id', $object->getId());
        $this->setDefault('name', __($object->getName()));
        $this->setDefault('monthly_basic', $object->getMonthlyBasic());
        $this->setDefault('other_allowance', $object->getOtherAllowance());
        $this->setDefault('monthly_basic_tax', $object->getMonthlyBasicTax());
        $this->setDefault('monthly_nopay_leave', $object->getMonthlyNopayLeave());
        $this->setDefault('monthly_epf_deduction', $object->getMonthlyEpfDeduction());
        $this->setDefault('monthly_etf_deduction', $object->getMonthlyEtfDeduction());
        $this->setDefault('jobtitle_id', $object->getJobtitleId());
    }

    /**
     * @return array|Doctrine_Record|SalaryType
     * @throws Doctrine_Connection_Exception
     * @throws Doctrine_Record_Exception
     * @throws ServiceException
     */
    public function getObject() {
        if ($this->isBound()) {
            $object = new SalaryType();

            $id = $this->getValue('id');
            if (!empty($id)) {
                $object = $this->getSalaryService()->getSalaryType($id);
            }

            $object->setName($this->getValue('name'));
            $object->setMonthlyBasic($this->getValue('monthly_basic'));
            $object->setOtherAllowance($this->getValue('other_allowance')?$this->getValue('other_allowance'):null);
            $object->setMonthlyBasicTax($this->getValue('monthly_basic_tax')?$this->getValue('monthly_basic_tax'):null);
            $object->setMonthlyNopayLeave($this->getValue('monthly_nopay_leave')?$this->getValue('monthly_nopay_leave'):null);
            $object->setMonthlyEpfDeduction($this->getValue('monthly_epf_deduction')?$this->getValue('monthly_epf_deduction'):null);
            $object->setMonthlyEtfDeduction($this->getValue('monthly_etf_deduction')?$this->getValue('monthly_etf_deduction'):null);
            $object->setJobtitleId($this->getValue('jobtitle_id')?$this->getValue('jobtitle_id'):null);




            return $object;
        } else {
            throw new Exception('Data values are not bound yet');
        }
    }

    /**
     *
     * @return array
     */
    public function getJavaScripts() {
        $javascripts = parent::getJavaScripts();

        $javascripts[] = '../orangehrmPayrollPlugin/js/viewSalaryTypeSuccess.js';
        return $javascripts;
    }

    /**
     *
     * @return array
     */
    public function getStylesheets() {
        $stylesheets = parent::getStylesheets();

        $stylesheets['../orangehrmPayrollPlugin/css/viewSalaryTypetSuccess.css'] = 'all';

        return $stylesheets;
    }

    /**
     *
     * @return array 
     */
    protected function getFormWidgets() {
        $widgets = array();

        $widgets['id'] = new sfWidgetFormInputHidden();
        $widgets['name'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));
        $widgets['monthly_basic'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));
        $widgets['other_allowance'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));
        $widgets['monthly_basic_tax'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));
        $widgets['monthly_nopay_leave'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));
        $widgets['monthly_epf_deduction'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));
        $widgets['monthly_etf_deduction'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));

        return $widgets;
    }

    /**
     *
     * @return array 
     */
    protected function getFormValidators() {
        $validators = array();

        $validators['id'] = new sfValidatorString(array('required' => false));
        $validators['name'] = new sfValidatorString(array('required' => true));
        $validators['monthly_basic'] = new sfValidatorString(array('required' => true));
        $validators['other_allowance'] = new sfValidatorString(array('required' => false));
        $validators['monthly_basic_tax'] = new sfValidatorString(array('required' => false));
        $validators['monthly_nopay_leave'] = new sfValidatorString(array('required' => false));
        $validators['monthly_epf_deduction'] = new sfValidatorString(array('required' => false));
        $validators['monthly_etf_deduction'] = new sfValidatorString(array('required' => false));

        return $validators;
    }

    /**
     *
     * @return array 
     */
    protected function getFormLabels() {
        $requiredLabelSuffix = ' <span class="required">*</span>';

        $labels = array(
            'name' => __('Salary Type Name') . $requiredLabelSuffix,
            'monthly_basic' => __('Monthly Basic Salary') . $requiredLabelSuffix,
            'other_allowance' => __('Other Allowance'),
            'monthly_basic_tax' => __('Monthly Tax for Basic Salary'),
            'monthly_nopay_leave' => __('No Pay Leave Deduction'),
            'monthly_epf_deduction' => __('EPF for Basic Salary'),
            'monthly_etf_deduction' => __('ETF for Basic Salary'),
            'jobtitle_id' => __('Job Title'),
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
