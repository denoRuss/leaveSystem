<?php


class generatePayrollSummaryReportAction extends viewPayrollSummaryReportAction
{
    public function execute($request)
    {
        $year = $request->getParameter('year');
        $month = $request->getParameter('month');

        $pdf = new PDFWrapper();
//        $pdf->setOrient('Landscape');
        $pdf->setHtml($this->getHtmlForReport($year,$month));
        $pdf->generatePDF();
        $fileName = $this->getFileName($year.'_'.$month);
        $pdf->viewPDF($fileName);
        return sfView::NONE;
    }
}