<?php


class generateBankLetterAction extends viewBankingLetterAction
{
    public function execute($request)
    {
        $publishDate = $request->getParameter('publishDate');
        $year = $request->getParameter('year');
        $month = $request->getParameter('month');

        $pdf = new PDFWrapper();
        $pdf->setHtml($this->getHtmlForReport($publishDate,$year,$month));
        $pdf->generatePDF();
        $fileName = $this->getFileName($publishDate);
        $pdf->viewPDF($fileName);
        return sfView::NONE;
    }

}