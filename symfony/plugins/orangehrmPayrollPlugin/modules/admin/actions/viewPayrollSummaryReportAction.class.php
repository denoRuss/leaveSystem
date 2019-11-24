<?php


class viewPayrollSummaryReportAction extends basePayrollAction
{   protected static $fp;
    public $csvReportName;

    public function execute($request)
    {
        $this->reportGenerateUrl  = url_for('admin/generatePayrollSummaryReport');
        $this->form = new PayrollSummaryReportForm();
        $hdnAction  = $request->getParameter('hdnAction');

        if($request->isMethod(sfRequest::POST)){
            $postData = $request->getParameter($this->form->getName());
            $this->form->bind($postData);

            if ($this->form->isValid()) {
                try {

                    $csvExport = true;
                    if($hdnAction=='view'){
                        $csvExport = false;
                    }
                    if($csvExport){

                        $csvContent = $this->getCsvForReport($postData);
                        $this->renderResponse($csvContent);


                        ob_clean();

                        $response = $this->getResponse();
                        $response->setHttpHeader('Pragma', 'public');
                        $response->setHttpHeader('Expires', '0');
                        $response->setHttpHeader("Cache-Control", "must-revalidate, post-check=0, pre-check=0, max-age=0");
                        $response->setHttpHeader("Cache-Control", "private", false);
                        $response->setHttpHeader("Content-Type", 'text/csv; charset=UTF-8');
                        $response->setHttpHeader("Content-Disposition", 'attachment; filename="' . $this->getCSVFileName() . '";');
                        $response->setHttpHeader("Content-Transfer-Encoding", "binary");

                        return $this->renderText($csvContent);


//                        return sfView::NONE;
                    }
                    else{
                        $pdf = new PDFWrapper();
                        $pdf->setHtml($this->getHtmlForReport($postData['publishDate'],$postData['year'],$postData['month']));
                        $pdf->generatePDF();
                        $fileName = $this->getFileName($postData['publishDate']);

                        $pdf->downloadPDF($fileName);

                        return sfView::NONE;
                    }


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


    public function getHtmlForReport($year=null,$month=null){

        $searchParams = array('year'=>$year,'month'=>$month);

        $results = $this->getSalaryService()->searchEmployeeSalaryHistory($searchParams);

        $totalPayment = 0;
        $tableBodyContent = '';

        $basicSalSum = 0;
        $grossSalSum = 0;
        $epf8SalSum = 0;
        $payeeTaxSum = 0;
        $totalDeductionSum = 0;
        $epf12Sum = 0;
        $etf3Sum = 0;
        $netSalarySum = 0;

        foreach ($results as $result){
            /**
             * @var $employee Employee
             * @var $result EmployeeSalaryHistory
             */

            $employee = $result->getEmployee();

            if(!is_null($employee->getIsExcluded())){
                continue;
            }

            $totalPayment =$totalPayment + $result->getTotalNetsalary();
            $employeeName = $employee->getCustom4() ? $employee->getCustom4():$employee->getFirstName().' '.$employee->getLastName();

            $tableBodyContent .="<tr >
                                    <td style='padding-bottom: 1.5em;'>{$employeeName}</td>
                                    <td style='padding-bottom: 1.5em;text-align: right;padding-right: 5px'>{$result->displayMonthlyBasic()}</td>
                                    <td style='padding-bottom: 1.5em;text-align: right;padding-right: 5px'>{$result->displayTotalEarnings()}</td>
                                    <td style='padding-bottom: 1.5em;text-align: right;padding-right: 5px'>{$result->displayMonthlyEpfDeduction()}</td>
                                    <td style='padding-bottom: 1.5em;text-align: right;padding-right: 5px'>{$result->displayMonthlyBasicTax()}</td>
                                    
                                    <td style='padding-bottom: 1.5em;text-align: right;padding-right: 5px'>{$result->dispalyTotalDeduction()}</td>
                                    <td style='padding-bottom: 1.5em;text-align: right;padding-right: 5px'>{$result->displayCompanyEpfDeduction()}</td>
                                    <td style='padding-bottom: 1.5em;text-align: right;padding-right: 5px'>{$result->displayMonthlyEtfDeduction()}</td>
                                    <td style='padding-bottom: 1.5em;text-align: right;padding-right: 5px'>{$result->displayTotalNetsalary()}</td>
                                </tr>";



            $basicSalSum += $result->getMonthlyBasic();
            $grossSalSum += $result->calculateTotalEarnings();
            $epf8SalSum += $result->getMonthlyEpfDeduction();
            $payeeTaxSum += $result->getMonthlyBasicTax();

            $totalDeductionSum += $result->calculateTotalDeduction();
            $epf12Sum += $result->getCompanyEpfDeduction();
            $etf3Sum += $result->getMonthlyEtfDeduction();
            $netSalarySum += $result->getTotalNetsalary();

        }


        $totalRow = array('',$basicSalSum,$grossSalSum,$epf8SalSum,$payeeTaxSum,$totalDeductionSum,$epf12Sum,$etf3Sum,$netSalarySum);
        foreach ($totalRow as $key=>$value){

            if($key == 0){
                continue;
            }

            $totalRow[$key] = number_format($value,2,'.',',');

        }

        $tableBodyContent .="<tr>
                                <td></td>
                                <td style='text-align: right;font-weight: bolder';padding-right: 5px>{$totalRow[1]}</td>
                                <td style='text-align: right;font-weight: bolder;padding-right: 5px'>{$totalRow[2]}</td>
                                <td style='text-align: right;font-weight: bolder;padding-right: 5px'>{$totalRow[3]}</td>
                                <td style='text-align: right;font-weight: bolder;padding-right: 5px'>{$totalRow[4]}</td>
                                <td style='text-align: right;font-weight: bolder;padding-right: 5px'>{$totalRow[5]}</td>
                                <td style='text-align: right;font-weight: bolder;padding-right: 5px'>{$totalRow[6]}</td>
                                <td style='text-align: right;font-weight: bolder;padding-right: 5px'>{$totalRow[7]}</td>
                                <td style='text-align: right;font-weight: bolder;padding-right: 5px'>{$totalRow[8]}</td>
                                
        </tr>
        ";


        $bankLetterTemplate = file_get_contents(sfConfig::get('sf_root_dir') . "/plugins/orangehrmPayrollPlugin/modules/admin/templates/reports/payrollsummary.txt");

        $bankLetterReplacementKeys = array(
            '/#tableBodyContent/',
            '/#month/'

        );

        $monthName = date('F Y',strtotime($year.'-'.$month.'-01'));
        $bankLetterReplacementValues  = array($tableBodyContent,$monthName);

        $htmlContent = preg_replace($bankLetterReplacementKeys, $bankLetterReplacementValues, $bankLetterTemplate);

        return $htmlContent;

    }

    public function getCsvForReport($postData){

        $headers = array(
            'NAME',
            'BASIC SALARY ',
            'G.SALARY ',
            'EPF 8%',
            'Payee tax',
            'Total deduction',
            'EPF 12%',
            'ETF 3%',
            'Net salary'

        );

        $csvContent = null;
        $year = $postData['year'];
        $month = $postData['month'];
        $this->csvReportName = $year."_".$month;
        $searchParams = array('year'=>$year,'month'=>$month);

        $results = $this->getSalaryService()->searchEmployeeSalaryHistory($searchParams);

        $totalPayment = 0;
        $csvResultSet = array ();

        $basicSalSum = 0;
        $grossSalSum = 0;
        $epf8SalSum = 0;
        $payeeTaxSum = 0;
        $totalDeductionSum = 0;
        $epf12Sum = 0;
        $etf3Sum = 0;
        $netSalarySum = 0;

        foreach ($results as $result){
            /**
             * @var $employee Employee
             * @var $result EmployeeSalaryHistory
             */

            $employee = $result->getEmployee();

            if(!is_null($employee->getIsExcluded())){
                continue;
            }
            $totalPayment =$totalPayment + $result->getTotalNetsalary();
            $csvRow = array();
            $csvRow[] = $employee->getCustom4() ? $employee->getCustom4():$employee->getFirstName().' '.$employee->getLastName(); //NAME
            $csvRow[] = $result->displayMonthlyBasic(); //BASIC SALARY
            $csvRow[] = $result->displayTotalEarnings(); //GROSS SALARY TODO
            $csvRow[] = $result->displayMonthlyEpfDeduction(); //EPF 8%
            $csvRow[] = $result->displayMonthlyBasicTax(); //PAYEE TAX

            $csvRow[] = $result->dispalyTotalDeduction(); //TOTAL DEDUCTION
            $csvRow[] = $result->displayCompanyEpfDeduction(); // EPF 12%
            $csvRow[] = $result->displayMonthlyEtfDeduction(); // ETF 3%
            $csvRow[] = $result->displayTotalNetsalary(); // NET SALARY

            $csvResultSet[] = $csvRow;

            $basicSalSum += $result->getMonthlyBasic();
            $grossSalSum += $result->calculateTotalEarnings();
            $epf8SalSum += $result->getMonthlyEpfDeduction();
            $payeeTaxSum += $result->getMonthlyBasicTax();

            $totalDeductionSum += $result->calculateTotalDeduction();
            $epf12Sum += $result->getCompanyEpfDeduction();
            $etf3Sum += $result->getMonthlyEtfDeduction();
            $netSalarySum += $result->getTotalNetsalary();

        }

        $totalRow = array('',$basicSalSum,$grossSalSum,$epf8SalSum,$payeeTaxSum,$totalDeductionSum,$epf12Sum,$etf3Sum,$netSalarySum);
        foreach ($totalRow as $key=>$value){

            if($key == 0){
                continue;
            }

            $totalRow[$key] = number_format($value,2,'.',',');

        }
        $csvResultSet[] = $totalRow;
        $csvContent = $this->createCSVString($headers,$csvResultSet);


        return $csvContent;

    }

    public function renderResponse($csvContent) {
        ob_clean();
        header("Content-Type: text/csv; charset=UTF-8");
        header("Pragma:''");
        header("Content-Disposition: attachment; filename=" . $this->getCSVFileName());

        echo $csvContent;
    }

    public function getFileName($publishDate = null){
        $ext = '_payroll_summary.pdf';
        return $publishDate.$ext;
    }


    public function getCSVFileName($publishDate=null){
        $ext = '_payroll_summary.csv';
        return $this->csvReportName.$ext;
    }
    public function createCSVString(array $headers, array $data, array $metaData = null) {
        self::$fp = fopen('php://temp/maxmemory:5120', 'r+');

        fputcsv(self::$fp, $headers);
        foreach ($data as $row) {
            fputcsv(self::$fp, $row);
        }
        rewind(self::$fp);
        $csvString  = stream_get_contents(self::$fp);
        fclose(self::$fp);

        return $csvString;
    }


}