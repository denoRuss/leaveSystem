<?php


class checkTaxBracketBoundsNotExistAjaxAction extends viewSalaryTypeListAction
{
    public function execute($request)
    {
        $lowerBound = $request->getParameter('lower_bound');
        $upperBound = $request->getParameter('upper_bound');
        $id = $request->getParameter('id');


        $taxBracketBoundsNotExist = $this->getSalaryService()->checkTaxBracketBoundsNotExist($lowerBound,$upperBound,$id);
        $this->getResponse()->setContent(json_encode($taxBracketBoundsNotExist));

        return sfView::NONE;
    }
}