<?php


class PayrollSummaryReportForm extends sfForm
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
        return $widgets;
    }

    protected function _setYearAndMonthWidget() {

        $yearChoices = $this->getYearList();
        $monthCoices = $this->getMonthList();

        $this->setWidget('year', new sfWidgetFormChoice(array('choices' => $yearChoices)));
        $this->setValidator('year', new sfValidatorChoice(array('choices' => array_keys($yearChoices))));

        $this->setWidget('month', new sfWidgetFormChoice(array('choices' => $monthCoices)));
        $defaultMonthChoices = array(1,2,3,4,5,6,7,8,9,10,11,12);
        $this->setValidator('month', new sfValidatorChoice(array('choices' => $defaultMonthChoices)));

        $this->setDefault('year', date('Y'));
        $this->setDefault('month', date('m'));
    }

    public function getFormValidators(){
        sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N', 'OrangeDate'));
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $validators = array();
        return $validators;
    }

    public function getFormLabels(){
        $requiredLabelSuffix = ' <span class="required">*</span>';
        $labels = array();
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