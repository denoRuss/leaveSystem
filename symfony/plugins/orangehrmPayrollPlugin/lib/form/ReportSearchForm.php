<?php


class ReportSearchForm extends sfForm
{
    public function configure()
    {
        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setNameFormat('report[%s]');
    }

    public function getFormWidgets(){
        $widgets = array();
        $widgets['calFromDate'] = new ohrmWidgetDatePicker(array(), array('id' => 'calFromDate'));
        $widgets['calToDate'] = new ohrmWidgetDatePicker(array(), array('id' => 'calToDate'));

        return $widgets;
    }

    public function getFormValidators(){

        sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N', 'OrangeDate'));
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $validators = array();

        $validators['calFromDate'] = new ohrmDateValidator(
            array('date_format' => $inputDatePattern, 'required' => true),
            array('invalid'=>'Date format should be'. $inputDatePattern));

        $validators['calToDate'] = new ohrmDateValidator(
            array('date_format' => $inputDatePattern, 'required' => false),
            array('invalid'=>'Date format should be'. $inputDatePattern));

        return $validators;
    }

    public function getFormLabels(){
        $requiredLabelSuffix = ' <span class="required">*</span>';

        $labels = array();
        $labels['calFromDate'] = __('From').$requiredLabelSuffix;
        $labels['calToDate'] = __('To').$requiredLabelSuffix;

        return $labels;
    }
}