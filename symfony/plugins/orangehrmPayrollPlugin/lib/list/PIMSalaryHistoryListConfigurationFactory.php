<?php


class PIMSalaryHistoryListConfigurationFactory extends ohrmListConfigurationFactory {

    /**
     *
     * @return string 
     */
    public function getClassName() {
        return 'EmployeeSalaryHistory';
    }



    /**
     * Overriding the init() method 
     */
    public function init() {
        $headers = array();

//        $header = new ListHeader();
//        $header->populateFromArray(array(
//            'width' => '40%',
//            'name' => 'Employee',
//            'elementType' => 'link',
//            'sortField' => 'name',
//            'isSortable' => true,
//            'filters' => array('I18nCellFilter' => array()),
//            'elementProperty' => array(
//                'labelGetter' => 'getName',
//                'urlPattern' => 'index.php/admin/viewSalaryType/id/{id}',
//                'placeholderGetters' => array('id' => 'getId'),
//            ),
//        ));
//        $headers[] = $header;

        $header = new ListHeader();
        $header->populateFromArray(array(
            'width' => '20%',
            'name' => 'Year',
            'elementType' => 'label',
            'filters' => array('I18nCellFilter' => array()),
            'elementProperty' => array('getter' => array('getYear')),
        ));
        $headers[] = $header;

        $header = new ListHeader();
        $header->populateFromArray(array(
            'width' => '20%',
            'name' => 'Month',
            'elementType' => 'label',
            'filters' => array('I18nCellFilter' => array()),
            'elementProperty' => array('getter' => array('getMonthName')),
        ));
        $headers[] = $header;

        $header = new ListHeader();
        $header->populateFromArray(array(
            'width' => '20%',
            'name' => 'Total Earnings',
            'elementType' => 'label',
            'filters' => array('I18nCellFilter' => array()),
            'elementProperty' => array('getter' => array('displayTotalEarnings')),
        ));
        $headers[] = $header;




        $header = new ListHeader();
        $header->populateFromArray(array(
            'width' => '20%',
            'name' => 'Net Salary',
            'elementType' => 'label',
            'filters' => array('I18nCellFilter' => array()),
            'elementProperty' => array('getter' => array('dispalyTotalNetsalary')),
        ));
        $headers[] = $header;

        $header = new ListHeader();
        $header->populateFromArray(array(
            'name' => 'Pay Slip',
            'width' => '20%',
            'isSortable' => false,
            'sortField' => 'lastName',
            'elementType' => 'pimLinkPaymentOption',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array(
                'labelGetter' => array('getter' => array('getId')),
                'placeholderGetters' => array('empNumber' => 'getEmpNumber'),
                'urlPattern' => public_path('index.php/admin/viewEmployeePayslip/empNumber/{empNumber}'),
            ),
        ));
        $headers[] = $header;


        $this->setHeaders($headers);
    }

}
