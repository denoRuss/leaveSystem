<?php


class orangehrmPayrollPluginConfiguration extends sfPluginConfiguration
{
    public function initialize() {
        $this->dispatcher->connect('routing.load_configuration', array($this, 'listenToRoutingLoadConfigurationEvent'));

    }

    /**
     * @param sfEvent $event
     */
    public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();
        /* Preprending new routes */
        $r->prependRoute('viewSalaryList', new sfRoute('/pim/viewSalaryList/*', array('module' => 'pim', 'action' => 'viewPersonalSalary')));
    }
}