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


    public function getHtmlForReport($publishDate,$year=null,$month=null){

        $searchParams = array('year'=>$year,'month'=>$month);

        $results = $this->getSalaryService()->searchEmployeeSalaryHistory($searchParams);

        $totalPayment = 0;
        $tableBodyContent = '';
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
            $tableBodyContent .="<tr style='height: 15px'>
								<td>".$employee->getFirstName().' '.$employee->getLastName()."</td>
								<td style='text-align: right'>{$result->dispalyTotalNetsalary()}</td>
								<td class='account-no-row'>{$employee->getCustom1()}</td>
								<td class='bank-row'>{$employee->getCustom2()}</td>
								<td class='branch-row'>{$employee->getCustom3()}</td>
							</tr>";
        }

        $totalPayment = number_format($totalPayment,2);
        $tableBodyContent .="<tr>
								<td class='total-row-label'>TOTAL</td>
							    <td class='total-row-value'>{$totalPayment}</td>
								<td ></td>
								<td ></td>
								<td ></td>
							</tr>";



        $bankLetterTemplate = file_get_contents(sfConfig::get('sf_root_dir') . "/plugins/orangehrmPayrollPlugin/modules/admin/templates/reports/bank.txt");

        $bankLetterReplacementKeys = array(
            '/#tableBodyContent/',
            '/#publishDate/',
            '/#addressDate/'

        );
        $addressDate = date('jS F Y');
        $bankLetterReplacementValues  = array($tableBodyContent,date('d.m.Y',strtotime($publishDate)),$addressDate);

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
            $csvRow[] = $employee->getFirstName().' '.$employee->getLastName(); //NAME
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