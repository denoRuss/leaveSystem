<?php


class DkSalaryService
{
    protected $salaryDao;


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

}