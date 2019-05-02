<?php


class makePaymentAction extends viewEmployeeListAction
{
    protected $employeeService;
    public function execute($request) {

        /* For highlighting corresponding menu item */
        $request->setParameter('initialActionName', 'makePayment');

        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }

        $empNumber = $request->getParameter('empNumber');
        $isPaging = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);

        $pageNumber = $isPaging;
        if (!empty($empNumber) && $this->getUser()->hasAttribute('pageNumber')) {
            $pageNumber = $this->getUser()->getAttribute('pageNumber');
        }

        $noOfRecords = sfConfig::get('app_items_per_page');

        $offset = ($pageNumber >= 1) ? (($pageNumber - 1) * $noOfRecords) : ($request->getParameter('pageNo', 1) - 1) * $noOfRecords;

        // Reset filters if requested to
        if ($request->hasParameter('reset')) {
            $this->setFilters(array());
            $this->setSortParameter(array("field"=> NULL, "order"=> NULL));
            $this->setPage(1);
        }

        $this->form = new EmployeePaymentSearchForm($this->getFilters());
        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {

                if($this->form->getValue('isSubmitted')=='yes'){
                    $this->setSortParameter(array("field"=> NULL, "order"=> NULL));
                }

                $this->setFilters($this->form->getValues());

            } else {
                $this->setFilters(array());
                $this->handleBadRequest();
                $this->getUser()->setFlash('search.warning', __(TopLevelMessages::VALIDATION_FAILED), false);
            }

            $this->setPage(1);
        }

        if ($request->isMethod('get')) {
            $sortParam = array("field"=>$request->getParameter('sortField'),
                "order"=>$request->getParameter('sortOrder'));
            $this->setSortParameter($sortParam);
            $this->setPage(1);
        }

        $sort = $this->getSortParameter();
        $sortField = $sort["field"];
        $sortOrder = $sort["order"];
        $filters = $this->getFilters();

        if( isset(  $filters['employee_name'])){
            $filters['employee_name'] = str_replace(' (' . __('Past Employee') . ')', '', $filters['employee_name']['empName']);
        }

        if (isset($filters['supervisor_name'])) {
            $filters['supervisor_name'] = str_replace(' (' . __('Past Employee') . ')', '', $filters['supervisor_name']);
        }

        $this->filterApply = !empty($filters);

        $accessibleEmployees = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntityIds('Employee');

        if (count($accessibleEmployees) > 0) {
            $filters['employee_id_list'] = $accessibleEmployees;
            $count = $this->getEmployeeService()->getSearchEmployeeCount( $filters );

            $parameterHolder = new EmployeeSearchParameterHolder();
            $parameterHolder->setOrderField($sortField);
            $parameterHolder->setOrderBy($sortOrder);
            $parameterHolder->setLimit($noOfRecords);
            $parameterHolder->setOffset($offset);
            $parameterHolder->setFilters($filters);

            $list = $this->getEmployeeService()->searchEmployees($parameterHolder);

        } else {
            $count = 0;
            $list = array();
        }

        $this->setListComponent($list, $count, $noOfRecords, $pageNumber);

        // Show message if list is empty, and we don't already have a message.
        if (empty($this->message) && (count($list) == 0)) {

            // Check to see if we have any employees in system
            $employeeCount = $this->getEmployeeService()->getEmployeeCount();
            $this->messageType = "warning";

            if (empty($employeeCount)) {
                $this->message = __("No Employees Available");
            } else {
                $this->message = __(TopLevelMessages::NO_RECORDS_FOUND);
            }

        }
    }

    protected function setListComponent($employeeList, $count, $noOfRecords, $page) {

        $configurationFactory = $this->getListConfigurationFactory();

        $runtimeDefinitions = array();
        $buttons = array();

        $allowedToAddEmployee = $this->getContext()->getUserRoleManager()->isActionAllowed(PluginWorkflowStateMachine::FLOW_EMPLOYEE,
            Employee::STATE_NOT_EXIST, PluginWorkflowStateMachine::EMPLOYEE_ACTION_ADD);

        if ($allowedToAddEmployee) {
            $buttons['Add'] = array('label' => 'Add');
        }

        $deleteActiveEmployee = $this->getContext()->getUserRoleManager()->isActionAllowed(PluginWorkflowStateMachine::FLOW_EMPLOYEE,
            Employee::STATE_ACTIVE, PluginWorkflowStateMachine::EMPLOYEE_ACTION_DELETE_ACTIVE);

        $deleteTerminatedEmployee = $this->getContext()->getUserRoleManager()->isActionAllowed(PluginWorkflowStateMachine::FLOW_EMPLOYEE,
            Employee::STATE_TERMINATED, PluginWorkflowStateMachine::EMPLOYEE_ACTION_DELETE_TERMINATED);

        if ($deleteActiveEmployee || $deleteTerminatedEmployee) {
            $buttons['Delete'] = array('label' => 'Delete',
                'type' => 'submit',
                'data-toggle' => 'modal',
                'data-target' => '#deleteConfModal',
                'class' => 'delete');
        } else {
            $runtimeDefinitions['hasSelectableRows'] = false;
        }

        $unDeletableIds = $this->getUndeletableEmpNumbers();
        if (count($unDeletableIds) > 0) {
            $runtimeDefinitions['unselectableRowIds'] = $unDeletableIds;
        }

        $runtimeDefinitions['buttons'] = $buttons;
        $configurationFactory->setRuntimeDefinitions($runtimeDefinitions);

        ohrmListComponent::setConfigurationFactory($configurationFactory);

        ohrmListComponent::setActivePlugin('orangehrmPayrollPlugin');
        ohrmListComponent::setListData($employeeList);
        ohrmListComponent::setItemsPerPage($noOfRecords);
        ohrmListComponent::setNumberOfRecords($count);
        ohrmListComponent::setPageNumber($page);
    }

    protected function getListConfigurationFactory() {
        $isPimAccessible = $this->getLeftMenuService()->isPimAccessible(null, false);
        $linkSetting = $isPimAccessible ? PaymentListConfigurationFactory::LINK_ALL_EMPLOYEES : PaymentListConfigurationFactory::LINK_NONE;
        PaymentListConfigurationFactory::setLinkSetting($linkSetting);

        $configurationFactory = new PaymentListConfigurationFactory();

        return $configurationFactory;
    }

    public function getEmployeeService() {
        if(is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new DkEmployeeDao());
        }
        return $this->employeeService;
    }
}