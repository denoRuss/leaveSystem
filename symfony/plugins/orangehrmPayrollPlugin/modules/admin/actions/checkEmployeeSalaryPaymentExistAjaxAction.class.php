<?php


class checkEmployeeSalaryPaymentExistAjaxAction extends viewSalaryTypeListAction
{
    public function execute($request) {
        $year = $request->getParameter('year');
        $month = $request->getParameter('month');
        $empNumber = $request->getParameter('empNumber');

        $salaryTypeNameDoesNotExist = $this->getSalaryService()->checkEmployeeSalaryPaymentExist($empNumber,$year, $month);

        $this->getResponse()->setContent(json_encode($salaryTypeNameDoesNotExist));

        return sfView::NONE;
    }
}