<?php


class monthlySalaryHistoryProcessCronTask extends sfBaseTask
{
    protected $salaryService;

    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'orangehrm'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('start', null, sfCommandOption::PARAMETER_OPTIONAL, 'Starting Month', ''),
            new sfCommandOption('end', null, sfCommandOption::PARAMETER_REQUIRED, 'Ending month', date('Y-m-d')),
            // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'monthly-salary-history-backup';
        $this->briefDescription = 'Save unprocessed salary record in previous month(s)';
        $this->detailedDescription = <<<EOF
The [monthly-salary-history-backup |INFO] task does things.
Call it with:

  [php symfony monthly-salary-history-backup|INFO]
EOF;
    }


    /**
     * Executes the current task.
     *
     * @param array $arguments An array of arguments
     * @param array $options An array of options
     *
     * @return integer 0 if everything went fine, or an error code
     */
    protected function execute($arguments = array(), $options = array())
    {

        // create context
        $context = sfContext::createInstance($this->configuration);

        // set date format
        $configService = new ConfigService();
        $datePattern = $configService->getAdminLocalizationDefaultDateFormat();
        $context->getUser()->setDateFormat($datePattern);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();



        $endDate = $options['end'];
        $startDate =$options['start'];

        if($startDate==''){
            $startDate = $endDate;
        }

        $realStartDateMonth = date('m', strtotime('-1 day', strtotime($startDate)));
        $endDateMonth = date('m',strtotime($endDate));
        $year = date('Y', strtotime('-1 day', strtotime($startDate)));

        $employeeSalaryRecords = $this->getSalaryService()->searchEmployeeSalaryRecords(array());

        foreach ($employeeSalaryRecords as $employeeSalaryRecord){

            /**
             * @var EmployeeSalaryRecord $employeeSalaryRecord
             */
            $empNumber = $employeeSalaryRecord->getEmpNumber();

            for($i=$realStartDateMonth ; $i< $endDateMonth; $i++){
                echo "process month $i \n";

                //check employee is eligible for salary reports
                if(!is_null($employeeSalaryRecord->getEmployee()->getIsExcluded())){
                    continue;
                }

                //check employee joined at least in processing month
                $joinedDate = $employeeSalaryRecord->getEmployee()->getJoinedDate();
                if(!is_null($joinedDate) && $joinedDate> date('Y-m-t',strtotime($year.'-'.$i.'-01'))){
                    continue;
                }

                //check whether payment has done
                $searchParams = array('emp_number'=>$empNumber,'month'=>$i,'year'=>$year);
                $employeeSalaryHistoryRecord = $this->getSalaryService()->searchEmployeeSalaryHistory($searchParams);
                if($employeeSalaryHistoryRecord instanceof EmployeeSalaryHistory){
                    continue;
                }
                //check existing adjusted record
                $backupEmployeeMonthlySalaryRecord = null;
                $searchParams['empNumber'] = $empNumber;
                $employeeMonthlySalaryRecord = $this->getSalaryService()->getEmployeeMonthlySalaryRecord($searchParams);
                if($employeeMonthlySalaryRecord instanceof EmployeeMonthlySalaryRecord){
                    $backupEmployeeMonthlySalaryRecord = $employeeMonthlySalaryRecord;
                }

                //add new EmployeeMonthlySalaryRecord for the previous month (Y-(m-1)) based on current month (Y-m-01) record
                $currentEmployeeSalaryRecord = $this->getSalaryService()->searchEmployeeSalaryRecord(array('emp_number'=>$empNumber));

                if(!$backupEmployeeMonthlySalaryRecord instanceof EmployeeMonthlySalaryRecord){
                    $backupEmployeeMonthlySalaryRecord = new EmployeeMonthlySalaryRecord();
                    $backupEmployeeMonthlySalaryRecord->setEmpNumber($empNumber);
                    $backupEmployeeMonthlySalaryRecord->setMonth($i);
                    $backupEmployeeMonthlySalaryRecord->setYear($year);
                }

                /**
                 * @var EmployeeSalaryRecord $currentEmployeeSalaryRecord
                 */
                $backupEmployeeMonthlySalaryRecord->setMonthlyBasic($currentEmployeeSalaryRecord->getMonthlyBasic());
                $backupEmployeeMonthlySalaryRecord->setOtherAllowance($currentEmployeeSalaryRecord->getOtherAllowance());
                $backupEmployeeMonthlySalaryRecord->setMonthlyBasicTax(
                    $currentEmployeeSalaryRecord->calculateMonthlyBasicTax($currentEmployeeSalaryRecord->getMonthlyBasic()));
                $backupEmployeeMonthlySalaryRecord->setMonthlyEpfDeduction(
                    $currentEmployeeSalaryRecord->calculateMonthlyEpfDeduction($currentEmployeeSalaryRecord->getMonthlyBasic()));
                $backupEmployeeMonthlySalaryRecord->setCompanyEpfDeduction(
                    $currentEmployeeSalaryRecord->calculateCompanyEpfDeduction($currentEmployeeSalaryRecord->getMonthlyBasic()));
                $backupEmployeeMonthlySalaryRecord->setMonthlyEtfDeduction(
                    $currentEmployeeSalaryRecord->calculateMonthlyEtfDeduction($currentEmployeeSalaryRecord->getMonthlyBasic()));


                //TODO leave count ?? leave payment??

                $this->getSalaryService()->saveEmployeeMonthlySalaryRecord($backupEmployeeMonthlySalaryRecord);

            }
        }

    }

    public function getSalaryService() {
        if (!($this->salaryService instanceof DkSalaryService)) {
            $this->salaryService = new DkSalaryService();
        }
        return $this->salaryService;
    }

}