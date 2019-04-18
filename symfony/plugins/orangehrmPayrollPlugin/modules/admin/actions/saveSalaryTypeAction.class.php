<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures 
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2012 OrangeHRM Inc., http://www.orangehrm.com
 *
 * Please refer the file license/LICENSE.TXT for the license which includes terms and conditions on using this software.
 *
 */
class saveSalaryTypeAction extends viewSalaryTypeListAction {

    /**
     *
     * @param sfRequest $request 
     */
    public function execute($request) {
        $message = null;
        $messageType = null;

        if ($request->isMethod(sfRequest::POST)) {
            $form = new SalaryTypeForm();
            $postData = $request->getParameter($form->getName());

            $form->bind($postData);

            if ($form->isValid()) {
                try {
                    $salaryType = $form->getObject();
                    $this->getSalaryService()->saveSalaryType($salaryType);
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
        $this->redirect('admin/viewSalaryTypeList');
    }

}
