<?php


class viewPayrollConfigurationAction extends basePayrollAction
{
    public function execute($request)
    {
        /* For highlighting corresponding menu item */
        $request->setParameter('initialActionName', 'viewPayrollConfiguration');

        if (!$this->getUser()->getAttribute('user')->isAdmin()) {
            $this->redirect('default/secure');
        }

        $form = new PayrollConfigForm();
        $form->setObject();

        $this->editable = false;
        $this->showEditbutton = true;
        $this->form = $form;
        $this->title = 'Payroll Configuration' ;
    }

}