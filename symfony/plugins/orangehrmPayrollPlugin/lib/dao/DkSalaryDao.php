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
}