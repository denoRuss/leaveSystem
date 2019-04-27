<?php


class TaxBracketForm extends sfForm {
    
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


    /**
     * Overriding the configure method 
     */
    public function configure() {
        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setNameFormat('tax_bracket[%s]');
    }


    /**
     *
     * @param SalaryComponent $object 
     */
    public function setObject(TaxBracket $object) {

        
        $this->setDefault('id', $object->getId());
        $this->setDefault('lower_bound', $object->getLowerBound());
        $this->setDefault('upper_bound', $object->getUpperBound());
        $this->setDefault('percentage', $object->getPercentage());

    }

    /**
     * @return array|Doctrine_Record|TaxBracket
     * @throws Doctrine_Connection_Exception
     * @throws Doctrine_Record_Exception
     * @throws ServiceException
     */
    public function getObject() {
        if ($this->isBound()) {
            $object = new TaxBracket();

            $id = $this->getValue('id');
            if (!empty($id)) {
                $object = $this->getSalaryService()->getTaxBracket($id);
            }

            $object->setLowerBound($this->getValue('lower_bound')?$this->getValue('lower_bound'):null);
            $object->setUpperBound($this->getValue('upper_bound')?$this->getValue('upper_bound'):null);
            $object->setPercentage($this->getValue('percentage')?$this->getValue('percentage'):null);

            return $object;
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

        $widgets['id'] = new sfWidgetFormInputHidden();
        $widgets['lower_bound'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));
        $widgets['upper_bound'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));
        $widgets['percentage'] = new sfWidgetFormInputText(array(), array('class' => 'formInputText'));


        return $widgets;
    }

    /**
     *
     * @return array 
     */
    protected function getFormValidators() {
        $validators = array();

        $validators['id'] = new sfValidatorString(array('required' => false));
        $validators['lower_bound'] = new sfValidatorString(array('required' => true));
        $validators['upper_bound'] = new sfValidatorString(array('required' => true));
        $validators['percentage'] = new sfValidatorString(array('required' => true));

        return $validators;
    }

    /**
     *
     * @return array 
     */
    protected function getFormLabels() {
        $requiredLabelSuffix = ' <span class="required">*</span>';

        $labels = array(
            'lower_bound' => __('Lower Bound') . $requiredLabelSuffix,
            'upper_bound' => __('Upper Bound') . $requiredLabelSuffix,
            'percentage' => __('Percentage') . $requiredLabelSuffix,
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
