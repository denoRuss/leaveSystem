<?php


class generatePaySlipAction extends basePayrollAction
{

    public function execute($request)
    {
        $pdf = new PDFWrapper();

        $pdf->setHtml('<h1>TEST</h1>');
        $pdf->generatePDF();
        $pdf->viewPDF('test.pdf');

        return sfView::NONE;
    }
}