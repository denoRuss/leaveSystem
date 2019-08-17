<?php


class viewTaxBracketListAction extends viewSalaryTypeListAction
{
    public function execute($request)
    {
        if (!$this->getUser()->getAttribute('user')->isAdmin()) {
            $this->redirect('default/secure');
        }
        $sortField= $request->getParameter('sortField','lower_bound');
        $sortOrder = $request->getParameter('sortOrder','ASC');
        $list = $this->getSalaryService()->getTaxBracketList($sortField,$sortOrder);
        $this->setListComponent($list);
    }

    public function setListComponent($listData) {

        $configurationFactory = new TaxBracketListConfigurationFactory();

        $buttons['Add'] = array('label' => 'Add');
//        $buttons['Delete'] = array('label' => 'Delete',
//            'type' => 'submit',
//            'data-toggle' => 'modal',
//            'data-target' => '#deleteConfModal',
//            'class' => 'delete');

        $runtimeDefinitions['buttons'] = $buttons;
        $configurationFactory->setRuntimeDefinitions($runtimeDefinitions);

        ohrmListComponent::setActivePlugin('orangehrmPayrollPlugin');
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        ohrmListComponent::setListData($listData);
    }

}