<?php


class TaxBracketListConfigurationFactory extends ohrmListConfigurationFactory {

    /**
     *
     * @return string 
     */
    public function getClassName() {
        return 'TaxBracket';
    }

    /**
     * Overriding the init() method 
     */
    public function init() {
        $headers = array();

        $header = new ListHeader();
        $header->populateFromArray(array(
            'width' => '10%',
            'name' => 'Id',
            'elementType' => 'link',
            'sortField' => 'id',
            'isSortable' => true,
            'filters' => array('I18nCellFilter' => array()),            
            'elementProperty' => array(
                'labelGetter' => 'getId',
                'urlPattern' => 'index.php/admin/viewTaxBracket/id/{id}',
                'placeholderGetters' => array('id' => 'getId'),
            ),
        ));
        $headers[] = $header;

        $header = new ListHeader();
        $header->populateFromArray(array(
            'width' => '40%',
            'name' => 'Lower bound',
            'elementType' => 'label',
            'filters' => array('I18nCellFilter' => array()),
            'elementProperty' => array('getter' => 'getLowerBound'),
        ));
        $headers[] = $header;

        $header = new ListHeader();
        $header->populateFromArray(array(
            'width' => '40%',
            'name' => 'Upper bound',
            'elementType' => 'label',
            'filters' => array('I18nCellFilter' => array()),
            'elementProperty' => array('getter' => 'getUpperBound'),
        ));
        $headers[] = $header;


        $header = new ListHeader();
        $header->populateFromArray(array(
            'width' => '10%',
            'name' => 'Tax value',
            'elementType' => 'label',
            'filters' => array('I18nCellFilter' => array()),
            'elementProperty' => array('getter' => 'getPercentage'),
        ));
        $headers[] = $header;



        $this->setHeaders($headers);
    }

}
