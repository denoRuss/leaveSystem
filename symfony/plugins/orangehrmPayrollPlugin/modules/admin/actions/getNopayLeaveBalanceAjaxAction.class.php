<?php


class getNopayLeaveBalanceAjaxAction extends basePayrollAction
{
    public function execute($request)
    {
        if (!$this->getUser()->getAttribute('user')->isAdmin()) {
            $this->redirect('default/secure');
        }
        $year = $request->getParameter('year');
        $month = $request->getParameter('month');
        $empNumber = $request->getParameter('empNumber');
        $from = date('Y-m-d',strtotime($year.'-'.$month.'-01'));
        $to = date('Y-m-t',strtotime($from));

        $noPayLeaveDeduction = $this->getSalaryService()->calulateNopayLeaveDeduction($empNumber,$from,$to);
        echo json_encode(array('nopayLeaveDeduction'=>$noPayLeaveDeduction));
        return sfView::NONE;
    }

}