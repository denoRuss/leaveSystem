<?php


class viewSalaryTypeAction extends viewSalaryTypeListAction
{


    public function execute($request)
    {
        /* For highlighting corresponding menu item */
        $request->setParameter('initialActionName', 'viewSalaryTypeList');

        if (!$this->getUser()->getAttribute('user')->isAdmin()) {
            $this->redirect('default/secure');
        }

        $id = $request->getParameter('id');
        $salaryType = $this->getSalaryService()->getSalaryType($id);
        $form = new SalaryTypeForm();
        $form->setObject($salaryType);

        $this->editable = true;
        $this->form = $form;
        $this->title = empty($id) ? 'Add Salary Type' : 'Edit Salary Type';
    }
}