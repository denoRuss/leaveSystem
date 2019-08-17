<?php


class BankLetterForm extends sfForm
{
    public function configure()
    {
        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());
        $this->_setYearAndMonthWidget();
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setNameFormat('bankletter[%s]');
    }

    public function getFormWidgets(){
        $widgets = array();
        $widgets['publishDate'] = new ohrmWidgetDatePicker(array(), array('id' => 'publishDate'));

        return $widgets;
    }

    protected function _setYearAndMonthWidget() {

        $yearChoices = $this->getYearList();
        $monthCoices = $this->getMonthList();

        $this->setWidget('year', new sfWidgetFormChoice(array('choices' => $yearChoices)));
        $this->setValidator('year', new sfValidatorChoice(array('choices' => array_keys($yearChoices))));

        $this->setWidget('month', new sfWidgetFormChoice(array('choices' => $monthCoices)));
        $this->setValidator('month', new sfValidatorChoice(array('choices' => array_keys($monthCoices))));
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
        $labels['publishDate'] = __('Transaction Date').$requiredLabelSuffix;
        $labels['year'] = __('Year').$requiredLabelSuffix;
        $labels['month'] = __('Month').$requiredLabelSuffix;

        return $labels;
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
}