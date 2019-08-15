<?php


class viewBankingLetterAction extends basePayrollAction
{
    public function execute($request)
    {
        $this->letterGenerateUrl  = url_for('admin/generateBankLetter');
        $this->form = new BankLetterForm();
        $hdnAction  = $request->getParameter('hdnAction');

        if($request->isMethod(sfRequest::POST)){
            $postData = $request->getParameter($this->form->getName());
            $this->form->bind($postData);

            if ($this->form->isValid()) {
                try {

                    $pdf = new PDFWrapper();
                    $pdf->setHtml($this->getHtmlForReport($postData['publishDate']));
                    $pdf->generatePDF();
                    $fileName = $this->getFileName($postData['publishDate']);

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

    public function getHtmlForReport($publishDate){




        $year = date('Y');
        $month = date('m');

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
								<td class='account-no-row'>{$employee->getCustom2()}</td>
								<td >{$employee->getCustom3()}</td>
							</tr>";
        }

        $totalPayment = number_format($totalPayment,2);
        $tableBodyContent .="<tr>
								<td class='total-row-label'>TOTAL</td>
							    <td class='total-row-label'>{$totalPayment}</td>
								<td ></td>
								<td ></td>
								<td ></td>
							</tr>";



        $epfReportTemplate = file_get_contents(sfConfig::get('sf_root_dir') . "/plugins/orangehrmPayrollPlugin/modules/admin/templates/reports/etf.txt");
        $bankLetterTemplate = file_get_contents(sfConfig::get('sf_root_dir') . "/plugins/orangehrmPayrollPlugin/modules/admin/templates/reports/bank.txt");

        $bankLetterReplacementKeys = array(
            '/#tableBodyContent/',
            '/#publishDate/',
            '/#addressDate/'

        );
        $addressDate = date('jS F Y',strtotime($publishDate));
        $bankLetterReplacementValues  = array($tableBodyContent,date('d.m.Y',strtotime($publishDate)),$addressDate);

        $htmlContent = preg_replace($bankLetterReplacementKeys, $bankLetterReplacementValues, $bankLetterTemplate);

        return $htmlContent;

    }

    public function getFileName($publishDate = null){
        $ext = '_bank_letter.pdf';
        return $publishDate.$ext;
    }
}