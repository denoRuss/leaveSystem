<?php


class saveEmployeeSalaryPaymentAction extends viewSalaryTypeListAction
{
    protected $emailPoolService;

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
                    $this->getEmailPoolService()->saveMakePaymentNotification(array($employeeSalaryHistory),$postData['month'],$postData['year']);
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
        $this->redirect('admin/makePayment');
    }

    /**
     * @return DkEmailPoolService
     */
    public function getEmailPoolService() {
        if (!($this->emailPoolService instanceof DkEmailPoolService)) {
            $this->emailPoolService = new DkEmailPoolService();
        }
        return $this->emailPoolService;
    }
}