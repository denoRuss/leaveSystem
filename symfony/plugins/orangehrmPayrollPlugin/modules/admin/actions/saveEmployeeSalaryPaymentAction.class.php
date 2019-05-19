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

                    $hdnAction = $postData['hdnAction'];

                    if($hdnAction==$form::MAKE_PAYMENT){

                        $employeeSalaryHistory = $form->getObject();

                        $this->getSalaryService()->saveEmployeeSalaryHistory($employeeSalaryHistory);
                        $this->getEmailPoolService()->saveMakePaymentNotification(array($employeeSalaryHistory),$postData['month'],$postData['year']);
                        $messageType = 'success';
                        $message = __('Payment Completed');
                    }
                    elseif ($hdnAction==$form::ADJUST_AND_MAKE_PAYMENT){

                        $modifiedEmployeeSalaryHistory = $form->getModifiedEmployeeSalaryHistory();
                        $this->getSalaryService()->saveEmployeeSalaryHistory($modifiedEmployeeSalaryHistory);
                        $this->getEmailPoolService()->saveMakePaymentNotification(array($modifiedEmployeeSalaryHistory),$postData['month'],$postData['year']);
                        $messageType = 'success';
                        $message = __('Payment Completed');
                    }
                    else{

                        $employeeMonthlyRecord = $form->getEmployeeMonthlySalaryRecord();
                        $this->getSalaryService()->saveEmployeeMonthlySalaryRecord($employeeMonthlyRecord);
                        $messageType = 'success';
                        $message = __('Salary Adjusted Successfully');
                    }
                } catch (Exception $e) {
                    $messageType = 'error';
                    $message = 'Failed to Save';
                }
            } else {
                $this->getUser()->setFlash('warning', __(TopLevelMessages::SAVE_FAILURE));
                $this->redirect($request->getReferer());
            }
        }
        $this->getUser()->setAttribute('showMakePaymentData', true);
        $this->getUser()->setFlash('templateMessage',array($messageType, __($message)));
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