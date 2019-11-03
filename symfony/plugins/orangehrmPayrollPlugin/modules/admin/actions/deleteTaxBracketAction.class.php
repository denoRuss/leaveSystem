<?php


class deleteTaxBracketAction extends basePayrollAction
{
    public function execute($request) {
        $message = null;
        $messageType = null;

        if ($request->isMethod(sfRequest::POST)) {
            $ids = $request->getParameter('chkSelectRow', array());
            $form = new DefaultListForm();
            $form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
                try {
                    if (empty($ids)) {
                        $message = __('No Records to Delete');
                        $messageType = 'warning';
                    } else {
                        $this->getSalaryService()->deleteTaxBrackets($ids);
                        $message = __(TopLevelMessages::DELETE_SUCCESS);
                        $messageType = 'success';
                    }
                } catch (ServiceException $e) {
                    $message = __($e->getMessage());
                    $messageType = 'error';
                }
            } else {
                $this->getUser()->setFlash('warning', __(TopLevelMessages::FORM_VALIDATION_ERROR));
                $this->redirect('admin/viewSalaryComponentList');
            }
        }
        $this->getUser()->setFlash($messageType, __($message));
        $this->redirect('admin/viewSalaryComponentList');
    }
}