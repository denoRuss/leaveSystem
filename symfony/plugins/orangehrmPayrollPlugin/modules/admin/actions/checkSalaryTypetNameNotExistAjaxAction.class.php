<?php


class checkSalaryTypetNameNotExistAjaxAction extends viewSalaryTypeListAction
{
    public function execute($request) {
        $id = $request->getParameter('id');
        $name = $request->getParameter('name');

        $salaryTypeNameDoesNotExist = $this->getSalaryService()->checkSalaryTypeNameNotExist($id, $name);

        $this->getResponse()->setContent(json_encode($salaryTypeNameDoesNotExist));

        return sfView::NONE;
    }
}