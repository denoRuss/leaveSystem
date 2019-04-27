<?php


class viewTaxBracketAction extends viewSalaryTypeListAction
{
    public function execute($request)
    {
        /* For highlighting corresponding menu item */
        $request->setParameter('initialActionName', 'viewTaxBracketList');

        if (!$this->getUser()->getAttribute('user')->isAdmin()) {
            $this->redirect('default/secure');
        }

        $id = $request->getParameter('id');
        $taxBracket = $this->getSalaryService()->getTaxBracket($id);
        $form = new TaxBracketForm();
        $form->setObject($taxBracket);

        $this->editable = true;
        $this->form = $form;
        $this->title = empty($id) ? 'Add Tax Bracket' : 'Edit Tax Bracket';
    }

}