<?php

class getMonthListForMakePaymentViewAjaxAction extends sfAction {

    public function execute($request) {
        
        $monthList = array();
        if ($request->getParameter('year') != "" && date("Y") != $request->getParameter('year')) {

            for ($i = 1; $i <= 12; $i++) {
                $monthList [date("n", strtotime("2012-" . $i . "-1"))] = date("F", strtotime("2012-" . $i . "-1"));
            }
        } elseif (date("Y") == $request->getParameter('year')) {
            for ($i = 1; $i <= date("n"); $i++) {
                $monthList [date("n", strtotime("2012-" . $i . "-1"))] = date("F", strtotime("2012-" . $i . "-1"));
            }
        }

        
        echo json_encode($monthList);
        return sfView::NONE;
    }

}