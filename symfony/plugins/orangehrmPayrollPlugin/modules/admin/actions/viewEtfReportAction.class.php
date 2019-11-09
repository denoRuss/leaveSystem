<?php


class viewEtfReportAction extends basePayrollAction
{
    public function execute($request)
    {
        $this->etfGenerateUrl  = url_for('admin/generateEtfReport');
        $this->form = new ReportSearchForm();
        $hdnAction  = $request->getParameter('hdnAction');

        if($request->isMethod(sfRequest::POST)){
            $postData = $request->getParameter($this->form->getName());
            $this->form->bind($postData);

            if ($this->form->isValid()) {
                try {

                    $pdf = new PDFWrapper();
                    $pdf->setHtml($this->getHtmlForReport($postData['checkNo'],$postData['calFromDate'],$postData['calToDate']));
                    $pdf->generatePDF();
                    $fileName = $this->getFileName($postData['calFromDate'],$postData['calToDate'],'ETF');

                    $pdf->downloadPDF($fileName);

                    return sfView::NONE;

                } catch (Exception $e) {
                    $messageType = 'error';
                    $message = 'Failed to Search';
                }
            }
            else {
                $this->getUser()->setFlash('warning', __(TopLevelMessages::VALIDATION_FAILED));
                $this->redirect($request->getReferer());
            }

            $this->getUser()->setFlash($messageType, __($message));
        }
    }

    public function getFileName($fromDate,$toDate,$reportType){

        $ext = '_report.pdf';
        if(is_null($toDate)){
            $toDate = date('Y-m-t');
        }

        return $reportType.'_'.$fromDate.'_to_'.$toDate.$ext;
    }

    public function getHtmlForReport($checkNo,$from,$to=null){

        $startYear = date('Y',strtotime($from));
        $startMonth = date('m',strtotime($from));

        if(is_null($to)){
            $to = date('Y-m-d');
        }

        $endYear = date('Y',strtotime($to));
        $endMonth = date('m',strtotime($to));

        $searchParams = array('startYear'=>$startYear,'startMonth'=>$startMonth,
            'endYear'=>$endYear,'endMonth'=>$endMonth);

        $results = $this->getSalaryService()->getSummaryOfETF($searchParams);

        $totalCompanyContribution = 0;
        $tableBodyContent = '';
        foreach ($results as $result){
            $formattedEmployeeName = empty($result['reportName']) ? $result['lastName'].' '.$result['firstName'] : $result['reportName'];
            $totalContPerEmployee = $result['etfContribution'];
            $totalCompanyContribution =$totalCompanyContribution + $totalContPerEmployee;
            $tableBodyContent .="<tr>
								<td>".$formattedEmployeeName."</td>
								<td>{$result['nic']}</td>
								<td class='nic-value-col'>{$result['memberNo']}</td>
								<td class='contribution-value-col'>".number_format($totalContPerEmployee,2)."</td>
							</tr>";
        }

        $tableBodyContent .="<tr>
								<td></td>
								<td></td>
								<td >TOTAL</td>
								<td class='contribution-value-col'>".number_format($totalCompanyContribution,2)."</td>
							</tr>";

        $monthAndYear = null;
        if($startMonth==$endMonth && $startYear==$endYear){
            $monthAndYear = date('F-Y',strtotime($from));
        }
        else{
            $monthAndYear = date('F-Y',strtotime($from)).' : '.date('F-Y',strtotime($to));
        }

        $epfReportTemplate = file_get_contents(sfConfig::get('sf_root_dir') . "/plugins/orangehrmPayrollPlugin/modules/admin/templates/reports/etf.txt");

        $epfReportReplacementKeys = array(
            '/#monthAndYear/',
            '/#totalCompanyContribution/',
            '/#tableBodyContent/',
            '/#checkNo/',

        );
        $epfReportReplacementValues  = array(
            $monthAndYear,
            number_format($totalCompanyContribution,2),
            $tableBodyContent,
            $checkNo
            );

        $htmlContent = preg_replace($epfReportReplacementKeys, $epfReportReplacementValues, $epfReportTemplate);

        return $htmlContent;

    }
}