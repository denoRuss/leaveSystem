<?php


class BankLetterForm extends sfForm
{
    public function configure()
    {
        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setNameFormat('bankletter[%s]');
    }

    public function getFormWidgets(){
        $widgets = array();
        $widgets['publishDate'] = new ohrmWidgetDatePicker(array(), array('id' => 'publishDate'));

        return $widgets;
    }

    public function getFormValidators(){
        sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N', 'OrangeDate'));
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $validators = array();

        $validators['publishDate'] = new ohrmDateValidator(
            array('date_format' => $inputDatePattern, 'required' => true),
            array('invalid'=>'Date format should be'. $inputDatePattern));

        return $validators;
    }

    public function getFormLabels(){
        $requiredLabelSuffix = ' <span class="required">*</span>';
        $labels = array();
        $labels['publishDate'] = __('Publish Date').$requiredLabelSuffix;

        return $labels;
    }
}