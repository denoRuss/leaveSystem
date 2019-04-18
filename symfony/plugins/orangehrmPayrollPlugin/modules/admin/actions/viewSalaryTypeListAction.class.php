<?php


class viewSalaryTypeListAction extends sfAction
{
    protected $salaryService;

    public function execute($request)
    {
        if (!$this->getUser()->getAttribute('user')->isAdmin()) {
            $this->redirect('default/secure');
        }
        $sortField= $request->getParameter('sortField');
        $sortOrder = $request->getParameter('sortOrder');
        $list = $this->getSalaryService()->getSalaryComponentList($sortField,$sortOrder);
        $this->setListComponent($list);
    }

    /**
     *
     * @param mixed $listData
     */
    public function setListComponent($listData) {

        $configurationFactory = new SalaryTypeListConfigurationFactory();

        $buttons['Add'] = array('label' => 'Add');
        $buttons['Delete'] = array('label' => 'Delete',
            'type' => 'submit',
            'data-toggle' => 'modal',
            'data-target' => '#deleteConfModal',
            'class' => 'delete');

        $runtimeDefinitions['buttons'] = $buttons;
        $configurationFactory->setRuntimeDefinitions($runtimeDefinitions);

        ohrmListComponent::setActivePlugin('orangehrmPayrollPlugin');
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        ohrmListComponent::setListData($listData);
    }


    public function getSalaryService() {
        if (!($this->salaryService instanceof DkSalaryService)) {
            $this->salaryService = new DkSalaryService();
        }
        return $this->salaryService;
    }


    public function setSalaryService(DkSalaryService $service) {
        $this->salaryService = $service;
    }
}