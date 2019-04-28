<?php


class savePayrollConfigurationAction extends basePayrollAction
{
    public function execute($request) {
        $message = null;
        $messageType = null;

        if ($request->isMethod(sfRequest::POST)) {
            $form = new PayrollConfigForm();
            $postData = $request->getParameter($form->getName());
            $form->bind($postData);

            if ($form->isValid()) {
                try {
                    $form->save();
                    $messageType = 'success';
                    $message = __('Confiuguration Saved');
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
        $this->redirect('admin/viewPayrollConfiguration');
    }
}