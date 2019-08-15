<?php


class generateBankLetterAction extends viewBankingLetterAction
{
    public function execute($request)
    {
        $publishDate = $request->getParameter('publishDate');

        $pdf = new PDFWrapper();
        $pdf->setHtml($this->getHtmlForReport($publishDate));
        $pdf->generatePDF();
        $fileName = $this->getFileName($publishDate);
        $pdf->viewPDF($fileName);
        return sfView::NONE;
    }

}