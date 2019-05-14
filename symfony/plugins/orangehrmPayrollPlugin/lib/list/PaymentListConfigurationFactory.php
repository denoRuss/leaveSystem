<?php

class PaymentListConfigurationFactory extends ohrmListConfigurationFactory {
    
    const LINK_NONE = 0;
    const LINK_ALL_EMPLOYEES = 1;    
    const LINK_ACTIVE_EMPLOYEES = 2;
    const LINK_DELETED_EMPLOYEES = 3;
    
    protected static $linkSetting = self::LINK_NONE;
    
    public function init() {
        sfContext::getInstance()->getConfiguration()->loadHelpers('OrangeDate');
        
        $header1 = new ListHeader();
        $header2 = new ListHeader();
        $header3 = new ListHeader();
        $header4 = new ListHeader();
        $header5 = new ListHeader();
        $header6 = new ListHeader();
        $header7 = new ListHeader();
        $header8 = new ListHeader();
        $header9 = new ListHeader();
        $header10 = new ListHeader();

        $header1->populateFromArray(array(
            'name' => 'Id',
            'width' => '5%',
            'isSortable' => true,
            'sortField' => 'employeeId',
            'elementType' => 'link',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array(
                'labelGetter' => array('getEmployeeId'),
                'placeholderGetters' => array('id' => 'getEmpNumber'),
                'linkable' => $this->getLinkable(),
                'urlPattern' => public_path('index.php/admin/employeeSalary/empNumber/{id}'),
            ),
        ));

        $header2->populateFromArray(array(
            'name' => __('First (& Middle) Name'),
            'width' => '13%',
            'isSortable' => true,
            'sortField' => 'firstMiddleName',
            'elementType' => 'link',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array(
                'labelGetter' => array('getFirstAndMiddleName'),
                'placeholderGetters' => array('id' => 'getEmpNumber'),
                'linkable' => $this->getLinkable(),
                'urlPattern' => public_path('index.php/admin/employeeSalary/empNumber/{id}'),
            ),
        ));

        $header3->populateFromArray(array(
            'name' => 'Last Name',
            'width' => '10%',
            'isSortable' => true,
            'sortField' => 'lastName',
            'elementType' => 'link',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array(
                'labelGetter' => array('getFullLastName'),
                'placeholderGetters' => array('id' => 'getEmpNumber'),
                'linkable' => $this->getLinkable(),
                'urlPattern' => public_path('index.php/admin/employeeSalary/empNumber/{id}'),
            ),
        ));

        $header4->populateFromArray(array(
            'name' => 'Job Title',
            'width' => '10%',
            'isSortable' => true,
            'sortField' => 'jobTitle',
            'elementType' => 'label',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array('getter' => 'getJobTitleName')
        ));

        $header5->populateFromArray(array(
            'name' => 'Gross Salary',
            'width' => '10%',
            'isSortable' => false,
            'sortField' => 'employeeStatus',
            'elementType' => 'label',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array('getter' => array('getEmployeeSalaryHistory','getFirst','getTotalEarning'))
        ));
        $header6->populateFromArray(array(
            'name' => 'Total Deduction',
            'width' => '10%',
            'isSortable' => false,
            'sortField' => 'employeeStatus',
            'elementType' => 'label',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array('getter' => array('getEmployeeSalaryHistory','getFirst','getTotalDeduction'))
        ));

        $header9->populateFromArray(array(
            'name' => 'Employer Contribution',
            'width' => '10%',
            'isSortable' => false,
            'sortField' => 'employeeStatus',
            'elementType' => 'label',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array('getter' => array('getEmployeeSalaryHistory','getFirst','getEmployerContribution'))
        ));
        $header7->populateFromArray(array(
            'name' => 'Net Salary',
            'width' => '10%',
            'isSortable' => false,
            'sortField' => 'employeeStatus',
            'elementType' => 'label',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array('getter' => array('getEmployeeSalaryHistory','getFirst','getTotalNetsalary'))
        ));
        $header8->populateFromArray(array(
            'name' => 'Status',
            'width' => '10%',
            'isSortable' => false,
            'sortField' => 'lastName',
            'elementType' => 'linkPayment',
            'filters' => array('PaymentStatusFilter' => array()),
            'textAlignmentStyle' => 'left',
            'elementProperty' => array(
                'labelGetter' => array('getter' => array('getEmployeeSalaryHistory','getFirst','getId')),
                'placeholderGetters' => array('id' => 'getEmpNumber'),
                'linkable' => $this->getLinkable(),
                'urlPattern' => public_path('index.php/admin/viewEmployeeSalaryPayment/empNumber/{id}'),
            ),
        ));
        $header10->populateFromArray(array(
            'name' => 'Pay Slip',
            'width' => '20%',
            'isSortable' => false,
            'sortField' => 'lastName',
            'elementType' => 'linkPaymentOption',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array(
                'labelGetter' => array('getter' => array('getEmployeeSalaryHistory','getFirst','getId')),
                'placeholderGetters' => array('empNumber' => 'getEmpNumber'),
                'linkable' => $this->getLinkable(),
                'urlPattern' => public_path('index.php/admin/viewEmployeePayslip/empNumber/{empNumber}'),
            ),
        ));



        $this->headers = array($header1, $header2, $header3, $header4, $header5, $header6,$header9,$header7,$header8,$header10);
    }
    
    public function getClassName() {
        return 'Employee';
    }
    
    public static function getLinkSetting() {
        return self::$linkSetting;
    }
    
    public static function setLinkSetting($setting) {
        self::$linkSetting = $setting;
    }
    
    public function getLinkable() {
        $linkable = false;
        
        if (self::$linkSetting == self::LINK_ALL_EMPLOYEES) {
            $linkable = true;            
        }
        
        return $linkable;
    }
}
