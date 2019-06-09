<?php


class generateEpfReportAction extends viewEpfReportAction
{
    public function execute($request)
    {
        $fromDate = $request->getParameter('from');
        $toDate = $request->getParameter('to');

        $pdf = new PDFWrapper();
        $pdf->setHtml($this->getHtmlForReport($fromDate,$toDate));
        $pdf->generatePDF();
        $fileName = $this->getFileName($fromDate,$toDate,'EPF');
        $pdf->viewPDF($fileName);
        return sfView::NONE;
    }
}