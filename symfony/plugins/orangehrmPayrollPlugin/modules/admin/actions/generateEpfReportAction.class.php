<?php


class generateEpfReportAction extends viewEpfReportAction
{
    public function execute($request)
    {
        $fromDate = $request->getParameter('from');
        $toDate = $request->getParameter('to');
        $checkNo = $request->getParameter('checkNo');

        $pdf = new PDFWrapper();
        $pdf->setHtml($this->getHtmlForReport($checkNo,$fromDate,$toDate));
        $pdf->generatePDF();
        $fileName = $this->getFileName($fromDate,$toDate,'EPF');
        $pdf->viewPDF($fileName);
        return sfView::NONE;
    }
}