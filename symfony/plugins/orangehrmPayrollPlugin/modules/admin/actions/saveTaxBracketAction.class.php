<?php


class saveTaxBracketAction extends viewSalaryTypeListAction
{
    public function execute($request)
    {
        $message = null;
        $messageType = null;

        if ($request->isMethod(sfRequest::POST)) {
            $form = new TaxBracketForm();
            $postData = $request->getParameter($form->getName());

            $form->bind($postData);

            if ($form->isValid()) {
                try {
                    $taxBracket = $form->getObject();
                    $this->getSalaryService()->saveTaxBracket($taxBracket);
                    $messageType = 'success';
                    $message = __(TopLevelMessages::SAVE_SUCCESS);
                } catch (Exception $e) {
                    $messageType = 'error';
                    $message = 'Failed to Save';
                }
            } else {
                $this->getUser()->setFlash('warning', __(TopLevelMessages::SAVE_FAILURE));
                $this->redirect($request->getReferer());
            }
        }
        $this->getUser()->setFlash($messageType, __($message));
        $this->redirect('admin/viewTaxBracketList');
    }
}