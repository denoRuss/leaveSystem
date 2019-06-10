<?php


class generateEtfReportAction extends viewEtfReportAction
{
    public function execute($request)
    {
        $fromDate = $request->getParameter('from');
        $toDate = $request->getParameter('to');

        $pdf = new PDFWrapper();
        $pdf->setHtml($this->getHtmlForReport($fromDate,$toDate));
        $pdf->generatePDF();
        $fileName = $this->getFileName($fromDate,$toDate,'ETF');
        $pdf->viewPDF($fileName);
        return sfView::NONE;
    }
}