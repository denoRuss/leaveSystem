<?php


class checkTaxBracketBoundsNotExistAjaxAction extends viewSalaryTypeListAction
{
    public function execute($request)
    {
        $lowerBound = $request->getParameter('lower_bound');
        $upperBound = $request->getParameter('upper_bound');


        $taxBracketBoundsNotExist = $this->getSalaryService()->checkTaxBracketBoundsNotExist($lowerBound,$upperBound);
        $this->getResponse()->setContent(json_encode($taxBracketBoundsNotExist));

        return sfView::NONE;
    }
}