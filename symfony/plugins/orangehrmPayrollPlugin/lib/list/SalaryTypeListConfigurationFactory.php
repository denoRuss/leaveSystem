<?php


class SalaryTypeListConfigurationFactory extends ohrmListConfigurationFactory {

    /**
     *
     * @return string 
     */
    public function getClassName() {
        return 'SalaryType';
    }

    /**
     * Overriding the init() method 
     */
    public function init() {
        $headers = array();

        $header = new ListHeader();
        $header->populateFromArray(array(
            'width' => '60%',
            'name' => 'Name',
            'elementType' => 'link',
            'sortField' => 'name',
            'isSortable' => true,
            'filters' => array('I18nCellFilter' => array()),            
            'elementProperty' => array(
                'labelGetter' => 'getName',
                'urlPattern' => 'index.php/admin/viewSalaryType/id/{id}',
                'placeholderGetters' => array('id' => 'getId'),
            ),
        ));
        $headers[] = $header;

        $header = new ListHeader();
        $header->populateFromArray(array(
            'width' => '40%',
            'name' => 'Job Title',
            'elementType' => 'label',
            'filters' => array('I18nCellFilter' => array()),
            'elementProperty' => array('getter' => array('getJobTitle','getjobTitleName')),
        ));
        $headers[] = $header;

//        $header = new ListHeader();
//        $header->populateFromArray(array(
//            'width' => '20%',
//            'name' => 'Part of Total Payable?',
//            'elementType' => 'label',
//            'filters' => array('I18nCellFilter' => array()),
//            'elementProperty' => array('getter' => 'getIsPartOfTotalPayableText'),
//        ));
//        $headers[] = $header;

//        $header = new ListHeader();
//        $header->populateFromArray(array(
//            'width' => '20%',
//            'name' => 'Cost to Company?',
//            'elementType' => 'label',
//            'filters' => array('I18nCellFilter' => array()),
//            'elementProperty' => array('getter' => 'getIsCostToCompanyText'),
//        ));
//        $headers[] = $header;

        $this->setHeaders($headers);
    }

}
