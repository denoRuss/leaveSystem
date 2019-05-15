<?php


class saveEmployeeSalaryRecordAction extends viewSalaryTypeListAction
{
    public function execute($request) {
        $message = null;
        $messageType = null;

        if ($request->isMethod(sfRequest::POST)) {
            $form = new EmployeeSalaryRecordForm();
            $postData = $request->getParameter($form->getName());

            $form->bind($postData);

            if ($form->isValid()) {
                try {
                    $employeeSalaryRecord = $form->getObject();
                    $this->getSalaryService()->saveEmployeeSalaryRecord($employeeSalaryRecord);
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

        $this->redirect($this->getRedirectUrl($postData));

    }

    public function getRedirectUrl($postData){

        $screen = $postData['screen'];
        $redirect = '';
        if($screen==EmployeeSalaryRecord::ADMIN_SCREEN){
            $redirect = 'admin/makePayment';
        }
        elseif ($screen==EmployeeSalaryRecord::PIM_SALARY_SCREEN){
            $redirect = 'pim/viewSalaryList?empNumber='.$postData['employee_name']['empId'];
        }

        return $redirect;
    }
}