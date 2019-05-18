<?php


class PimLinkPaymentOptionCell extends LinkPaymentOptionCell {

    public function __toString() {

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

        $salaryHistoryRecordId = $this->dataObject->getRawValue()->getId();
        $linkAttributes1 = array(
            'href' => $url.'/id/'.$salaryHistoryRecordId.'/mode/view',
            'target'=>'_blank'
        );
        $linkAttributes2 = array(
            'href' => $url.'/id/'.$salaryHistoryRecordId.'/mode/download',
        );

        $label1 = __('View');
        $label2 = __('Download');


        return content_tag('a', $label1, $linkAttributes1)
            . $this->getHiddenFieldHTML().'<br><br>'.content_tag('a', $label2, $linkAttributes2);
    }
}
