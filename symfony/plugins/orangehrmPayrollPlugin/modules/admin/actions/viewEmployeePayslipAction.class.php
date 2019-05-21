<?php


class viewEmployeePayslipAction extends basePayrollAction
{
    protected $organizationService;

    public function execute($request)
    {
        $id  = $request->getParameter('id');
        $mode  = $request->getParameter('mode');

        try{
            $employeeSalaryHistoryRecord =$this->getSalaryService()->getEmployeeSalaryHistory($id);

            if($employeeSalaryHistoryRecord instanceof EmployeeSalaryHistory){

                if (!$this->IsActionAccessible($employeeSalaryHistoryRecord->getEmpNumber())) {
//                    $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
                    return sfView::NONE;
                }
                $pdf = new PDFWrapper();
                $pdf->setHtml($this->getHtmlForPayslip($employeeSalaryHistoryRecord));
                $pdf->generatePDF();

                $fileName = $this->getFileName($employeeSalaryHistoryRecord);
                if($mode=='view'){
                    $pdf->viewPDF($fileName);
                }
                else if($mode == 'download'){
                    $pdf->downloadPDF($fileName);
                }
            }
        }
        catch (Exception $exception){
            Logger::getLogger('orangehrm')->error($exception->getMessage());
        }



        return sfView::NONE;
    }

    public function getHtmlForPayslip(EmployeeSalaryHistory $employeeSalaryHistoryRecord){

        $payslipTemplate = file_get_contents(sfConfig::get('sf_root_dir') . "/plugins/orangehrmPayrollPlugin/modules/admin/templates/payslip/payslip.txt");
        $payslipReplacementKeys = array(
            '/#employeeName/',
            '/#workEmail/',
            '/#fromDate/',
            '/#toDate/',
            '/#year/',
            '/#employeeId/',
            '/#companyName/',
            '/#joinedDate/',
            '/#grossIncome/',
            '/#companyEPFPercentage/',
            '/#companyEPFDeduction/',
            '/#etfPercentage/',
            '/#etfDeduction/',
            '/#employerContribution/',
            '/#monthlyEPFPercentage/',
            '/#monthlyEPFDeduction/',
            '/#unpaidLeave/',
            '/#monthlySalaryTax/',
            '/#totalDeduction/',
            '/#netSalary/',




            );
        $payslipReplacementValues = array();



        $payslipReplacementValues[] = $employeeSalaryHistoryRecord->getEmployee()->getFullName();
        $payslipReplacementValues[] = $employeeSalaryHistoryRecord->getEmployee()->getEmpWorkEmail();

//        TODO from date & to date need to chnage if fix payperiod is used
        $fromDate = $employeeSalaryHistoryRecord->getYear().'-'.$employeeSalaryHistoryRecord->getMonth().'-01';
        $payslipReplacementValues[] = date('F-d',strtotime($fromDate));
        $payslipReplacementValues[] = date('F-t',strtotime($fromDate));


        $payslipReplacementValues[] = $employeeSalaryHistoryRecord->getYear();
        $payslipReplacementValues[] = $employeeSalaryHistoryRecord->getEmployee()->getEmployeeId();

        $organization = $this->getOrganizationService()->getOrganizationGeneralInformation();
        $payslipReplacementValues[] =  $organization instanceof Organization? $organization->getName():'';

        $joinedDate = $employeeSalaryHistoryRecord->getEmployee()->getJoinedDate();
        $payslipReplacementValues[] = is_null($joinedDate)?'Not Defined':date('F d, Y',strtotime($joinedDate));

        $payslipReplacementValues[] = $employeeSalaryHistoryRecord->displayTotalEarnings();

        $payslipReplacementValues[] = $this->getSalaryConfigService()->getCompanyEpfPercentage();
        $payslipReplacementValues[] = $employeeSalaryHistoryRecord->displayCompanyEpfDeduction();

        $payslipReplacementValues[] = $this->getSalaryConfigService()->getEtfPercentage();
        $payslipReplacementValues[] = $employeeSalaryHistoryRecord->displayMonthlyEtfDeduction();
        $payslipReplacementValues[] = $employeeSalaryHistoryRecord->displayEmployerContribution();

        $payslipReplacementValues[] = $this->getSalaryConfigService()->getEpfPercentage();
        $payslipReplacementValues[] = $employeeSalaryHistoryRecord->displayMonthlyEpfDeduction();
        $payslipReplacementValues[] = $employeeSalaryHistoryRecord->displayMonthlyNopayLeave();
        $payslipReplacementValues[] = $employeeSalaryHistoryRecord->displayMonthlyBasicTax();

        $payslipReplacementValues[] = $employeeSalaryHistoryRecord->dispalyTotalDeduction();
        $payslipReplacementValues[] = $employeeSalaryHistoryRecord->displayTotalNetsalary();


        $htmlContent = preg_replace($payslipReplacementKeys, $payslipReplacementValues, $payslipTemplate);

        return $htmlContent;
    }

    public function getOrganizationService() {
        if (is_null($this->organizationService)) {
            $this->organizationService = new OrganizationService(new OrganizationDao());
        }
        return $this->organizationService;
    }

    public function getFileName($employeeSalaryHistoryRecord){

        $ext = "_paysheet.pdf";

        return $employeeSalaryHistoryRecord->getEmployee()->getFullName().
            '_'.$employeeSalaryHistoryRecord->getYear().'_'.
            date('F',strtotime('1970'.$employeeSalaryHistoryRecord->getMonth().'-01')).$ext;
    }
}