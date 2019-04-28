<?php


class saveEmployeeSalaryPaymentAction extends viewSalaryTypeListAction
{
    public function execute($request) {
        $message = null;
        $messageType = null;

        if ($request->isMethod(sfRequest::POST)) {
            $form = new EmployeeSalaryPaymentForm();
            $postData = $request->getParameter($form->getName());
            $form->bind($postData);

            if ($form->isValid()) {
                try {
                    $employeeSalaryHistory = $form->getObject();

                    $this->getSalaryService()->saveEmployeeSalaryHistory($employeeSalaryHistory);
                    $messageType = 'success';
                    $message = __('Payment Completed');
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
        $this->redirect('admin/viewEmployeeSalaryPayment/empNumber/'+$postData['employee_name']['empId']);
    }
}