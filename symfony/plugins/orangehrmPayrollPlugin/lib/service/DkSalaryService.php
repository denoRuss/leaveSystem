<?php


class DkSalaryService
{
    protected $salaryDao;
    protected $leaveRequestService;
    protected $salaryConfigService;
    protected $emailPoolService;


    /**
     * @return DkSalaryDao
     */
    public function getSalaryDao() {
        if (!($this->salaryDao instanceof DkSalaryDao)) {
            $this->salaryDao = new DkSalaryDao();
        }
        return $this->salaryDao;
    }

    /**
     * @param DkSalaryDao $salaryComponentDao
     */
    public function setSalaryComponentDao(DkSalaryDao $salaryComponentDao) {
        $this->salaryDao = $salaryComponentDao;
    }

    /**
     * @param string $sortField
     * @param string $sortOrder
     * @return Doctrine_Collection
     * @throws ServiceException
     */
    public function getSalaryComponentList($sortField='name',$sortOrder='ASC') {
        try {
            return $this->getSalaryDao()->getSalaryTypeList($sortField,$sortOrder);
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * @param string $sortField
     * @param string $sortOrder
     * @return Doctrine_Collection
     * @throws ServiceException
     */
    public function getTaxBracketList($sortField='id',$sortOrder='ASC') {
        try {
            return $this->getSalaryDao()->getTaxBracketList($sortField,$sortOrder);
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array|Doctrine_Record|SalaryType
     * @throws Doctrine_Connection_Exception
     * @throws Doctrine_Record_Exception
     * @throws ServiceException
     */
    public function getSalaryType($id) {
        if (empty($id)) {
            return new SalaryType();
        }

        try {
            return $this->getSalaryDao()->getSalaryType($id);
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array|Doctrine_Record|TaxBracket
     * @throws Doctrine_Connection_Exception
     * @throws Doctrine_Record_Exception
     * @throws ServiceException
     */
    public function getTaxBracket($id) {
        if (empty($id)) {
            return new TaxBracket();
        }

        try {
            return $this->getSalaryDao()->getTaxBracket($id);
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array|Doctrine_Record|EmployeeSalaryRecord
     * @throws Doctrine_Connection_Exception
     * @throws Doctrine_Record_Exception
     * @throws ServiceException
     */
    public function getEmployeeSalaryRecord($id) {
        if (empty($id)) {
            return new EmployeeSalaryRecord();
        }

        try {
            return $this->getSalaryDao()->getEmployeeSalaryRecord($id);
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array|Doctrine_Record|EmployeeSalaryHistory
     * @throws Doctrine_Connection_Exception
     * @throws Doctrine_Record_Exception
     * @throws ServiceException
     */
    public function getEmployeeSalaryHistory($id) {
        if (empty($id)) {
            return new EmployeeSalaryHistory();
        }

        try {
            return $this->getSalaryDao()->getEmployeeSalaryHistory($id);
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    public function searchEmployeeSalaryHistory($searchParams){
        return $this->getSalaryDao()->searchEmployeeSalaryHistory($searchParams);
    }

    /**
     * @param SalaryType $salaryType
     * @return mixed
     * @throws ServiceException
     */
    public function saveSalaryType(SalaryType $salaryType) {
        try {
            return $this->getSalaryDao()->saveSalaryType($salaryType);
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * @param TaxBracket $taxBracket
     * @return TaxBracket
     * @throws ServiceException
     */
    public function saveTaxBracket(TaxBracket $taxBracket) {
        try {
            return $this->getSalaryDao()->saveTaxBracket($taxBracket);
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * @param EmployeeSalaryRecord $employeeSalaryRecord
     * @return EmployeeSalaryRecord
     * @throws ServiceException
     */
    public function saveEmployeeSalaryRecord(EmployeeSalaryRecord $employeeSalaryRecord) {
        try {
            return $this->getSalaryDao()->saveEmployeeSalaryRecord($employeeSalaryRecord);
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * @param EmployeeSalaryHistory $employeeSalaryHistory
     * @return EmployeeSalaryHistory
     * @throws ServiceException
     */
    public function saveEmployeeSalaryHistory(EmployeeSalaryHistory $employeeSalaryHistory) {
        try {
            return $this->getSalaryDao()->saveEmployeeSalaryHistory($employeeSalaryHistory);
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * @param $employeeMonthlySalaryRecord
     * @return mixed
     * @throws ServiceException
     */
    public function saveEmployeeMonthlySalaryRecord($employeeMonthlySalaryRecord){
        try {
            return $this->getSalaryDao()->saveEmployeeMonthlySalaryRecord($employeeMonthlySalaryRecord);
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $name
     * @return bool
     * @throws ServiceException
     */
    public function checkSalaryTypeNameNotExist($id, $name) {
        try {
            $salaryType = $this->getSalaryDao()->getSalaryTypeByName($name);
            if ($salaryType === false) {
                return true;
            } else {
                if ($salaryType instanceof SalaryType) {
                    return ($id == $salaryType->getId());
                } else {
                    return true;
                }
            }
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * @param $empNumber
     * @param $year
     * @param $month
     * @return bool
     * @throws ServiceException
     */
    public function checkEmployeeSalaryPaymentExist($empNumber,$year, $month){
        try {
            $employeeSalaryPayment = $this->getSalaryDao()->searchEmployeeSalaryHistory(array(
                'emp_number'=>$empNumber,
                'year'=>$year,
                'month'=>$month
            ));
            if (count($employeeSalaryPayment)==1) {
                return false;
            }
            return true;

        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * @param $lowerBound
     * @param $upperBound
     * @return bool
     * @throws DaoException
     */
    public function checkTaxBracketBoundsNotExist($lowerBound,$upperBound,$id){

        if(empty($lowerBound)|| empty($upperBound)){
            return true;
        }
        return $this->getSalaryDao()->checkTaxBracketBoundsNotExist(array('lower_bound'=>$lowerBound,'upper_bound'=>$upperBound,'id'=>$id));

    }

    /**
     * @param $salary
     * @return array|Doctrine_Record
     * @throws DaoException
     */
    public function getMatchingTaxBracket($salary){
        return $this->getSalaryDao()->getMatchingTaxBracket($salary);
    }

    public function calulateNopayLeaveDeduction($empNumber,$from = null,$to = null){

        if($this->getSalaryConfigService()->getNopayLeaveTypeId()==-1 || $this->getSalaryConfigService()->getNopayLeaveDeduction()==0){
            return 0;
        }
        if(is_null($from) && is_null($to)){
            $from = date('Y-m-').'01';
            $to = date('Y-m-t');
        }

        $leaveTypeId = $this->getSalaryConfigService()->getNopayLeaveTypeId();
        $statuses = array(Leave::LEAVE_STATUS_LEAVE_TAKEN);

        try{

            $query = Doctrine_Query::create()
                ->select('SUM(l.length_days) as num_of_leave')
                ->from('Leave l');

                if (!empty($from)) {
                    $query->andWhere("l.date >= ?",$from);
                }

                if (!empty($to)) {
                    $query->andWhere("l.date <= ?",$to);
                }

                if (!empty($leaveTypeId)) {
                    $query->andWhere('l.leave_type_id = ?', $leaveTypeId);
                }
                $query->andWhere('l.emp_number = ?',$empNumber);

                if (!empty($statuses)) {
                    $query->andWhereIn("l.status", $statuses);
                }

            $result=  $query->execute();
        }
        catch (Exception $e){
            $result =false;
        }

        if($result instanceof Doctrine_Collection){
            $numOfTakenNopayLeave =$result->getFirst()['num_of_leave'];
        }
        $deductionForNopayLeave = $this->getSalaryConfigService()->getNopayLeaveDeduction();
        return $numOfTakenNopayLeave *$deductionForNopayLeave;
    }

    public function calculateNopayLeaveDedcutionBasedOnSalary($monthlyBasic,$nopayLeaveCount){
        return number_format($nopayLeaveCount*($monthlyBasic/30),2,'.','');
    }
    public function processBulkPayment($filters,$employeeList){
        $result = array();
        $result[0] = array('Payment Completed successfully');
        $result[1] = 'success.nofade';

        //set one time for performance improvement
        $from = date('Y-m-d',strtotime($filters['year'].'-'.$filters['month'].'-01'));
        $to = date('Y-m-t',strtotime($from));

        $searchParam = array('year'=>$filters['year'],'month'=>$filters['month']);
        $salaryHistoryList = new Doctrine_Collection(Doctrine::getTable('EmployeeSalaryHistory'));

        foreach ($employeeList as $employee){
            $searchParam['emp_number']=$employee->getEmpNumber();
            $employeePayment = $this->searchEmployeeSalaryHistory($searchParam);
            if(count($employeePayment)==0){

                //TODO this is still pending
                $nopayLeaveCount = 0;

                //check already edited monthly salary record exists
                $searchParam['empNumber'] = $employee->getEmpNumber();
                $existingMonthlySalaryRecord = $this->getSalaryDao()->getEmployeeMonthlySalaryRecord($searchParam);
                if($existingMonthlySalaryRecord instanceof EmployeeMonthlySalaryRecord){
                    $employeeCurrentSalaryRecord = $existingMonthlySalaryRecord;
                    $nopayLeaveCount = $existingMonthlySalaryRecord->getNopayLeaveCount();
                }
                else{
                    $employeeCurrentSalaryRecord = $this->getSalaryDao()->getEmployeeSalaryRecordByEmpNumber($employee->getEmpNumber());
                }

                if($employeeCurrentSalaryRecord instanceof EmployeeSalaryRecord || $employeeCurrentSalaryRecord instanceof EmployeeMonthlySalaryRecord){

                    $salaryHistoryItem = new EmployeeSalaryHistory();
                    $salaryHistoryItem->setEmpNumber($employee->getEmpNumber());
                    $salaryHistoryItem->setMonthlyBasic($employeeCurrentSalaryRecord->getMonthlyBasic());
                    $salaryHistoryItem->setOtherAllowance($employeeCurrentSalaryRecord->getOtherAllowance());
                    $salaryHistoryItem->setMonthlyBasicTax($employeeCurrentSalaryRecord->getMonthlyBasicTax());

                    //TODO this is still pending
                    $salaryHistoryItem->setMonthlyNopayLeave($this->calculateNopayLeaveDedcutionBasedOnSalary($employeeCurrentSalaryRecord->getMonthlyBasic(),
                        $nopayLeaveCount));

                    $salaryHistoryItem->setMonthlyEpfDeduction($employeeCurrentSalaryRecord->getMonthlyEpfDeduction());
                    $salaryHistoryItem->setCompanyEpfDeduction($employeeCurrentSalaryRecord->getCompanyEpfDeduction());
                    $salaryHistoryItem->setMonthlyEtfDeduction($employeeCurrentSalaryRecord->getMonthlyEtfDeduction());
                    $salaryHistoryItem->setTotalEarning($salaryHistoryItem->calculateTotalEarnings());
                    $salaryHistoryItem->setTotalDeduction($salaryHistoryItem->calculateTotalDeduction());
                    $salaryHistoryItem->setTotalNetsalary($salaryHistoryItem->calculateTotalNetsalary());
                    $salaryHistoryItem->setMonth($filters['month']);
                    $salaryHistoryItem->setYear($filters['year']);

                    $salaryHistoryList->add($salaryHistoryItem);
                }
                else{
                    $result[0][] = 'Basic Salary is not defined for '.$employee->getFullName();
                }
            }
            else{
                $result[0][] = 'Payment is already completed for '.$employee->getFullName().' for '.date('Y M',strtotime($from));
            }
        }

        try{
            $savedSalaryList =$salaryHistoryList->save();

            $this->getEmailPoolService()->saveMakePaymentNotification($savedSalaryList,$filters['month'],$filters['year']);

        }
        catch (Exception $exception){
            $result[0] = array('Failed to Process');
            $result[1] = 'warning';
            Logger::getLogger('orangehrm')->error($exception->getMessage());
        }
        return $result;
    }


    public function deleteTaxBrackets($ids){
        try {
            return $this->getSalaryDao()->deleteTaxBrackets($ids);
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * @param $searchParams
     * @return array|Doctrine_Record
     * @throws ServiceException
     */
    public function getEmployeeMonthlySalaryRecord($searchParams){
        try {
            return $this->getSalaryDao()->getEmployeeMonthlySalaryRecord($searchParams);
        } catch (DaoException $e) {
            throw new ServiceException($e->getMessage());
        }
    }
    public function getLeaveRequestService(){
        if (!($this->leaveRequestService instanceof LeaveRequestService)) {
            $this->leaveRequestService = new LeaveRequestService();
        }
        return $this->leaveRequestService;
    }

    public function getSalaryConfigService(){
        if (!($this->salaryConfigService instanceof DkConfigService)) {
            $this->salaryConfigService = new DkConfigService();
        }
        return $this->salaryConfigService;
    }

    public function getEmailPoolService(){
        if (!($this->emailPoolService instanceof DkEmailPoolService)) {
            $this->emailPoolService = new DkEmailPoolService();
        }
        return $this->emailPoolService;
    }

}