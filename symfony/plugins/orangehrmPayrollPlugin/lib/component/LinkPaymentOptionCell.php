<?php

class LinkPaymentOptionCell extends LinkCell {

    private $dataSourceType = self::DATASOURCE_TYPE_OBJECT;

    public function __toString() {

        $linkable = $this->isLinkable($this->dataObject);

        if (($linkable instanceof sfOutputEscaperArrayDecorator) || is_array($linkable)) {
            list($method, $params) = $linkable;
            $linkable = call_user_func_array(array($this->dataObject, $method), $params->getRawValue());
        }

        if ($linkable) {
            $placeholderGetters = $this->getPropertyValue('placeholderGetters');
            $urlPattern = $this->getPropertyValue('urlPattern');

            $url = $urlPattern;

            if (!is_null($placeholderGetters)) {
                foreach ($placeholderGetters as $placeholder => $getter) {
                    $placeholderValue = ($this->getDataSourceType() == self::DATASOURCE_TYPE_ARRAY) ? $this->dataObject[$getter] : $this->dataObject->$getter();
                    $url = preg_replace("/\{{$placeholder}\}/", $placeholderValue, $url);
                }
            }

            if (preg_match('/^index.php/', $url)) {
                sfProjectConfiguration::getActive()->loadHelpers('Url');
                $url = public_path($url, true);
            }

            $salaryHistoryRecordId = $this->dataObject->getRawValue()->getEmployeeSalaryHistory()->getFirst()->getId();
            $linkAttributes1 = array(
                'href' => $url.'/id/'.$salaryHistoryRecordId.'/mode/view',
            );
            $linkAttributes2 = array(
                'href' => $url.'/id/'.$salaryHistoryRecordId.'/mode/download',
            );

            $label1 = __('View');
            $label2 = __('Download');


            return content_tag('a', $label1, $linkAttributes1)
                . $this->getHiddenFieldHTML().content_tag('br').content_tag('a', $label2, $linkAttributes2);
        } else {
            return $this->toValue() . $this->getHiddenFieldHTML();
        }
    }


    public function isLinkable($dataObject = null){


        return !is_null($dataObject->getRawValue()->getEmployeeSalaryHistory()->getFirst()->getId());
    }

    protected function getValue($getterName = 'getter') {
        $getter = $this->getPropertyValue($getterName);
        $default = $this->getPropertyValue('default');
        if ($getter instanceof sfOutputEscaperArrayDecorator || is_array($getter)) {
            $getter =$getter->getRawValue()['getter'];
            $value = $this->dataObject;
            foreach ($getter as $method) {
                if (is_object($value)) {
                    $value = $value->$method();
                }
            }
        } else {
            $value = ($this->dataSourceType === self::DATASOURCE_TYPE_ARRAY) ? $this->dataObject[$getter] : $this->dataObject->$getter();
        }

        if (!$value && $default) {
            return $default;
        }

        $value = $this->filterValue($value);

        return $value;
    }

}
