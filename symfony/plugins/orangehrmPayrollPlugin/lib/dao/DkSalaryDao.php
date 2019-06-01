<?php


class DkSalaryDao
{
    /**
     * @param string $sortField
     * @param string $sortOrder
     * @return Doctrine_Collection
     * @throws DaoException
     */
    public function getSalaryTypeList($sortField='name',$sortOrder='ASC'){

        $sortField = "name";
        $sortOrder = ($sortOrder =='DESC')?'DESC':'ASC';
        try {
            $query = Doctrine_Query::create()
                ->from('SalaryType')
                ->orderBy($sortField.' '.$sortOrder);
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param string $sortField
     * @param string $sortOrder
     * @return Doctrine_Collection
     * @throws DaoException
     */
    public function getTaxBracketList($sortField='id',$sortOrder='ASC'){

        try {
            $query = Doctrine_Query::create()
                ->from('TaxBracket')
                ->orderBy($sortField.' '.$sortOrder);
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array|Doctrine_Record
     * @throws DaoException
     */
    public function getSalaryType($id) {
        try {
            $query = Doctrine_Query::create()
                ->from('SalaryType')
                ->where('id = ?', $id);

            return $query->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array|Doctrine_Record
     * @throws DaoException
     */
    public function getTaxBracket($id) {
        try {
            $query = Doctrine_Query::create()
                ->from('TaxBracket')
                ->where('id = ?', $id);

            return $query->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array|Doctrine_Record
     * @throws DaoException
     */
    public function getEmployeeSalaryRecord($id) {
        try {
            $query = Doctrine_Query::create()
                ->from('EmployeeSalaryRecord')
                ->where('id = ?', $id);

            return $query->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param $searchParams
     * @return array|Doctrine_Record
     * @throws DaoException
     */
    public function searchEmployeeSalaryRecord($searchParams){
        try {
            $query = Doctrine_Query::create()
                ->from('EmployeeSalaryRecord');

                if(isset($searchParams['emp_number'])){
                    $query->andWhere('emp_number = ?',$searchParams['emp_number']);
                }

            return $query->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }


    /**
     * @param $empNumber
     * @return array|Doctrine_Record
     * @throws DaoException
     */
    public function getEmployeeSalaryRecordByEmpNumber($empNumber) {
        try {
            $query = Doctrine_Query::create()
                ->from('EmployeeSalaryRecord')
                ->where('emp_number = ?', $empNumber);

            return $query->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array|Doctrine_Record
     * @throws DaoException
     */
    public function getEmployeeSalaryHistory($id) {
        try {
            $query = Doctrine_Query::create()
                ->from('EmployeeSalaryHistory')
                ->where('id = ?', $id);

            return $query->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param $searchParams
     * @return Doctrine_Collection
     * @throws DaoException
     */
    public function searchEmployeeSalaryHistory($searchParams) {
        try {
            $query = Doctrine_Query::create()
                ->from('EmployeeSalaryHistory');

            if(isset($searchParams['emp_number'])){
                $query ->where('emp_number = ?', $searchParams['emp_number']);
            }
            if(isset($searchParams['year'])){
                $query ->andWhere('year = ?', $searchParams['year']);
            }
            if(isset($searchParams['month'])){
                $query ->andWhere('month = ?', $searchParams['month']);
            }


            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param SalaryType $salaryComponent
     * @return SalaryType
     * @throws DaoException
     */
    public function saveSalaryType(SalaryType $salaryType) {
        try {
            $salaryType->save();
            return $salaryType;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param TaxBracket $taxBracket
     * @return TaxBracket
     * @throws DaoException
     */
    public function saveTaxBracket(TaxBracket $taxBracket) {
        try {
            $taxBracket->save();
            return $taxBracket;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param EmployeeSalaryRecord $employeeSalaryRecord
     * @return EmployeeSalaryRecord
     * @throws DaoException
     */
    public function saveEmployeeSalaryRecord(EmployeeSalaryRecord $employeeSalaryRecord) {
        try {
            $employeeSalaryRecord->save();
            return $employeeSalaryRecord;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param EmployeeSalaryHistory $employeeSalaryHistory
     * @return EmployeeSalaryHistory
     * @throws DaoException
     */
    public function saveEmployeeSalaryHistory(EmployeeSalaryHistory $employeeSalaryHistory){
        try {
            $employeeSalaryHistory->save();
            return $employeeSalaryHistory;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param $employeeMonthlySalaryRecord
     * @return mixed
     * @throws DaoException
     */
    public function saveEmployeeMonthlySalaryRecord($employeeMonthlySalaryRecord){
        try {
            $employeeMonthlySalaryRecord->save();
            return $employeeMonthlySalaryRecord;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param $name
     * @return array|Doctrine_Record
     * @throws DaoException
     */
    public function getSalaryTypeByName($name) {
        try {
            $query = Doctrine_Query::create()
                ->from('SalaryType')
                ->where('name = ?', $name);

            return $query->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param $searchParams
     * @return bool
     * @throws DaoException
     */
    public function checkTaxBracketBoundsNotExist($searchParams){
        try {
            $query = Doctrine_Query::create()
                ->from('TaxBracket');


            if(!empty($searchParams['lower_bound'])){
                $query->andWhere('upper_bound >= ?',$searchParams['lower_bound']);
            }
            if(!empty($searchParams['upper_bound'])){
                $query->andWhere('lower_bound <= ?',$searchParams['upper_bound']);
            }
            if(!empty($searchParams['id'])){
                $query->andWhere('id != ?',$searchParams['id']);
            }

            $result= $query->execute();

            if(count($result)==0){
                return true;
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param $salary
     * @return array|Doctrine_Record
     * @throws DaoException
     */
    public function getMatchingTaxBracket($salary){
        try {
            $query = Doctrine_Query::create()
                ->from('TaxBracket');

                $query->andWhere('upper_bound >= ?',$salary);
                $query->andWhere('lower_bound <= ?',$salary);

                return $query->fetchOne();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param $ids
     * @return Doctrine_Collection
     * @throws DaoException
     */
    public function deleteTaxBrackets($ids){
        try {
            $query = Doctrine_Query::create()
                ->delete('TaxBracket')
                ->whereIn('id', $ids);

            return $query->execute();
        } catch (Exception $e) {
            $message = $e->getMessage();
            throw new DaoException($message);
        }
    }

    /**
     * @param $searchParams
     * @return array|Doctrine_Record
     * @throws DaoException
     */
    public function getEmployeeMonthlySalaryRecord($searchParams){
        try {
            $query = Doctrine_Query::create()
                ->from('EmployeeMonthlySalaryRecord');


            if(isset($searchParams['empNumber'])){
                $query->andWhere('emp_number = ?',$searchParams['empNumber']);
            }
            if(isset($searchParams['month'])){
                $query->andWhere('month = ?',$searchParams['month']);
            }
            if(isset($searchParams['year'])){
                $query->andWhere('year = ?',$searchParams['year']);
            }

            return $query->fetchOne();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function getProcessedSalaryHisorySummary($searchParams){
        try {

            $sql = "SELECT count(*) as records ,h.emp_number as empNumber ,e.joined_date as joinedDate
                    FROM `dk_employee_salary_history` h
                    LEFT JOIN hs_hr_employee e ON h.emp_number = e.emp_number
                    WHERE h.`monthly_basic` >= ? AND h.`monthly_basic` <= ?
                    GROUP BY h.emp_number";

            $bindParams = array($searchParams['lower_bound'],$searchParams['upper_bound']);


            $pdo = Doctrine_Manager::connection()->getDbh();
            $query = $pdo->prepare($sql);
            $query->execute($bindParams);
            $results = $query->fetchAll(PDO::FETCH_ASSOC);

            return $results;

        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }
}