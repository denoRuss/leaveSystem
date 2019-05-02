<?php

class LinkPaymentCell extends LinkCell {



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

            $linkAttributes = array(
                'href' => $url,
            );

            $label = $this->getLabel();


            return content_tag('a', $label, $linkAttributes)
                . $this->getHiddenFieldHTML();
        } else {
            return $this->toValue() . $this->getHiddenFieldHTML();
        }
    }


    public function isLinkable($dataObject = null){


        return $dataObject->getRawValue()->getEmployeeSalaryHistory()->getFirst()->getMonthlyBasic()==null;
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
