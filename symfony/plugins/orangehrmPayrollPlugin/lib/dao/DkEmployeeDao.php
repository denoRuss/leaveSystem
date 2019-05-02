<?php


class DkEmployeeDao extends EmployeeDao
{

    protected static $searchMapping = array(
        'id' => 'e.employee_id',
        'employee_name' => 'concat_ws(\' \', e.emp_firstname,e.emp_middle_name,e.emp_lastname)',
        'middleName' => 'e.emp_middle_name',
        'lastName' => 'e.emp_lastName',
        'job_title' => 'j.job_title',
        'employee_status' => 'es.estat_name',
        'sub_unit' => 'cs.name',
        'supervisor_name' => 'concat_ws(\' \', se.emp_firstname,se.emp_middle_name,se.emp_lastname)',
        'supervisorId' => 's.emp_firstname',
        'termination' => 'e.termination_id',
        'location' => 'l.location_id',
        'employee_id_list' => 'e.emp_number',
        'gender' => 'e.emp_gender',
        'dob'   => 'e.emp_birthday',
        'month'=>'d.month',
        'year'=>'d.year'
    );
    private function _getEmployeeListQuery(&$select, &$query, array &$bindParams, &$orderBy,
        $sortField = null, $sortOrder = null, array $filters = null, $includePurgeEmployee = false) {

        $searchByTerminated = EmployeeSearchForm::WITHOUT_TERMINATED;

        /*
	     * Using direct SQL since it is difficult to use Doctrine DQL or RawSQL to get an efficient
	     * query taht searches the company structure tree and supervisors.
        */



        $select = 'SELECT e.emp_number AS empNumber, e.employee_id AS employeeId, ' .
            'e.emp_firstname AS firstName, e.emp_lastname AS lastName, ' .
            'e.emp_middle_name AS middleName,e.emp_birthday AS employeeBirthday, e.termination_id AS terminationId, ' .
            'cs.name AS subDivision, cs.id AS subDivisionId,' .
            'j.job_title AS jobTitle, j.id AS jobTitleId, j.is_deleted AS isDeleted, ' .
            'd.total_earning as totalEarning, d.total_deduction as totalDeduction, d.total_netsalary as netSalary,d.monthly_basic as monthlyBasic,'.
            'es.name AS employeeStatus, es.id AS employeeStatusId, '.
            'GROUP_CONCAT(s.emp_firstname, \'## \', s.emp_middle_name, \'## \', s.emp_lastname, \'## \',s.emp_number) AS supervisors,'.
            'GROUP_CONCAT(DISTINCT loc.id, \'##\',loc.name) AS locationIds';


        $query = 'FROM hs_hr_employee e ' .
            '  LEFT JOIN ohrm_subunit cs ON cs.id = e.work_station ' .
            '  LEFT JOIN ohrm_job_title j on j.id = e.job_title_code ' .
            '  LEFT JOIN ohrm_employment_status es on e.emp_status = es.id ' .
            '  LEFT JOIN hs_hr_emp_reportto rt on e.emp_number = rt.erep_sub_emp_number ' .
            '  LEFT JOIN hs_hr_employee s on s.emp_number = rt.erep_sup_emp_number '.
            '  LEFT JOIN hs_hr_emp_locations l ON l.emp_number = e.emp_number ' .
            '  LEFT JOIN dk_employee_salary_history d ON d.emp_number = e.emp_number AND d.month = ?' .
            '  LEFT JOIN ohrm_location loc ON l.location_id = loc.id';

        /* search filters */
        $conditions = array();
        $bindParams[]= $filters['month'];
        if (!empty($filters)) {

            $filterCount = 0;

            foreach ($filters as $searchField=>$searchBy ) {
                if (!empty($searchField) && !empty($searchBy)
                    && array_key_exists($searchField, self::$searchMapping) ) {
                    $field = self::$searchMapping[$searchField];

                    if ($searchField == 'sub_unit') {

                        /*
                         * Not efficient if searching substations by more than one value, but
                         * we only have the facility to search by one value in the UI.
                        */
                        $conditions[] =  'e.work_station IN (SELECT n.id FROM ohrm_subunit n ' .
                            'INNER JOIN ohrm_subunit p WHERE n.lft >= p.lft ' .
                            'AND n.rgt <= p.rgt AND p.id = ? )';
                        $bindParams[] = $searchBy;
                    } else if ($searchField == 'id') {
                        $conditions[] = ' e.employee_id LIKE ? ';
                        $bindParams[] = $searchBy;
                    } else if ($searchField == 'job_title') {
                        $conditions[] = ' j.id = ? ';
                        $bindParams[] = $searchBy;
                    }
                    else if ($searchField == 'employee_status') {
                        $conditions[] = ' es.id = ? ';
                        $bindParams[] = $searchBy;
                    } else if ($searchField == 'gender') {
                        $conditions[] = ' e.emp_gender = ? ';
                        $bindParams[] = $searchBy;
                    } else if ($searchField == 'dob') {
                        $conditions[] = ' e.emp_birthday = ? ';
                        $bindParams[] = $searchBy;
                    } else if ($searchField == 'supervisorId') {
                        $subordinates = $this->_getSubordinateIds($searchBy);
                        if (count($subordinates) > 0) {
                            $conditions[] = ' e.emp_number IN (' . implode(',', $subordinates) . ') ';
                        } else {
                            $conditions[] = ' s.emp_number = ? ';
                            $bindParams[] = $searchBy;
                        }
                    } else if ($searchField == 'employee_id_list') {
                        $conditions[] = ' e.emp_number IN (' . implode(',', $searchBy) . ') ';
                    } else if ($searchField == 'supervisor_name') {
                        // $conditions[] = $field . ' LIKE ? ';
                        $conditions[] =  ' e.emp_number IN ((SELECT srt.erep_sub_emp_number  FROM hs_hr_emp_reportto  srt LEFT JOIN hs_hr_employee se on ( srt.erep_sup_emp_number = se.emp_number )
                        					WHERE '. $field.' LIKE ? ))';
                        // Replace multiple spaces in string with wildcards
                        $value = preg_replace('!\s+!', '%', $searchBy);
                        $bindParams[] = '%' . $value . '%';

                        // $conditions[] = " e.emp_number IN (SELECT erep_sup_emp_number FROM hs_hr_emp_reportto where erep_sub_emp_number = e.emp_number))";
                    } else if ($searchField == 'employee_name') {
                        $conditions[] = $field . ' LIKE ? ';
                        // Replace multiple spaces in string with wildcards
                        $value = preg_replace('!\s+!', '%', $searchBy);
                        $bindParams[] = '%' . $value . '%';
                    } elseif( $searchField == 'location' ) {
                        if (!empty($filters['location']) && $filters['location'] != '-1') {
                            $locations = explode(',', $filters['location']);
                            $bindParams = array_merge($bindParams, $locations);
                            $conditions[] = ' l.location_id IN (' . implode(',', array_fill(0, count($locations), '?')) . ') ';
                        }
                    }
                    $filterCount++;

                    if ($searchField == 'termination') {
                        $searchByTerminated = $searchBy;
                    }
                }
            }
        }

        /* If not searching by employee status, hide terminated employees */
        if ($searchByTerminated == EmployeeSearchForm::WITHOUT_TERMINATED) {
            $conditions[] = "( e.termination_id IS NULL )";
        }

        if ($searchByTerminated == EmployeeSearchForm::ONLY_TERMINATED) {
            $conditions[] = "( e.termination_id IS NOT NULL )";
        }
        if (!$includePurgeEmployee) {
            $conditions[] = "( e.purged_at IS NULL )";
        }



        /* Build the query */
        $numConditions = 0;
        foreach ($conditions as $condition) {
            $numConditions++;

            if ($numConditions == 1) {
                $query .= ' WHERE ' . $condition;
            } else {
                $query .= ' AND ' . $condition;
            }
        }

        /* Group by */
        $query .= ' GROUP BY e.emp_number ';

        /* sorting */
        $order = array();

        if( !empty($sortField) && !empty($sortOrder) ) {
            if( array_key_exists($sortField, self::$sortMapping) ) {
                $field = self::$sortMapping[$sortField];
                if (is_array($field)) {
                    foreach ($field as $name) {
                        $order[$name] = $sortOrder;
                    }
                } else {
                    $order[$field] = $sortOrder;
                }
            }
        }

        /* Default sort by emp_number, makes resulting order predictable, useful for testing */
        $order['e.emp_lastname'] = 'asc';

        /* Sort subordinates direct first, then indirect, then by supervisor name */
        $order['rt.erep_reporting_mode'] = 'asc';

        if ($sortField != 'supervisor') {
            $order['s.emp_firstname'] = 'asc';
            $order['s.emp_lastname'] = 'asc';
        }
        $order['e.emp_number'] = 'asc';

        /* Build the order by part */
        $numOrderBy = 0;
        foreach ($order as $field=>$dir) {
            $numOrderBy++;
            if ($numOrderBy == 1) {
                $orderBy = ' ORDER BY ' . $field . ' ' . $dir;
            } else {
                $orderBy .= ', ' . $field . ' ' . $dir;
            }
        }

    }

    private function _getSubordinateIds($supervisorId) {

        $subordinatesList = $this->getSubordinateList($supervisorId, true);

        $ids = array();

        foreach ($subordinatesList as $employee) {
            $ids[] = intval($employee->getEmpNumber());
        }

        return $ids;
    }

    public function searchEmployees(EmployeeSearchParameterHolder $parameterHolder) {

        $sortField  = $parameterHolder->getOrderField();
        $sortOrder  = $parameterHolder->getOrderBy();
        $offset     = $parameterHolder->getOffset();
        $limit      = $parameterHolder->getLimit();
        $filters    = $parameterHolder->getFilters();
        $returnType = $parameterHolder->getReturnType();

        $select = '';
        $query = '';
        $bindParams = array();
        $orderBy = '';

        $this->_getEmployeeListQuery($select, $query, $bindParams, $orderBy,
            $sortField, $sortOrder, $filters);

        $completeQuery = $select . ' ' . $query . ' ' . $orderBy;

        if (!is_null($offset) && !is_null($limit)) {
            $completeQuery .= ' LIMIT ' . $offset . ', ' . $limit;
        }

        if (sfConfig::get('sf_logging_enabled')) {
            $msg = $completeQuery;
            if (count($bindParams) > 0 ) {
                $msg .=  ' (' . implode(',', $bindParams) . ')';
            }
            sfContext::getInstance()->getLogger()->info($msg);
        }

        $conn = Doctrine_Manager::connection();
        $statement = $conn->prepare($completeQuery);
        $result = $statement->execute($bindParams);

        if ($returnType == EmployeeSearchParameterHolder::RETURN_TYPE_OBJECT) {
            $employees = new Doctrine_Collection(Doctrine::getTable('Employee'));

            if ($result) {
                while ($row = $statement->fetch() ) {
                    $employee = new Employee();

                    $employee->setEmpNumber($row['empNumber']);
                    $employee->setEmployeeId($row['employeeId']);
                    $employee->setFirstName($row['firstName']);
                    $employee->setMiddleName($row['middleName']);
                    $employee->setLastName($row['lastName']);
                    $employee->setTerminationId($row['terminationId']);
                    $employee->setEmpBirthday($row['employeeBirthday']);

                    $jobTitle = new JobTitle();
                    $jobTitle->setId($row['jobTitleId']);
                    $jobTitle->setJobTitleName($row['jobTitle']);
                    $jobTitle->setIsDeleted($row['isDeleted']);
                    $employee->setJobTitle($jobTitle);

                    $employeeStatus = new EmploymentStatus();
                    $employeeStatus->setId($row['employeeStatusId']);
                    $employeeStatus->setName($row['employeeStatus']);
                    $employee->setEmployeeStatus($employeeStatus);

                    $workStation = new SubUnit();
                    $workStation->setName($row['subDivision']);
                    $workStation->setId($row['subDivisionId']);
                    $employee->setSubDivision($workStation);


                    $salaryHistoryList = new Doctrine_Collection(Doctrine::getTable('EmployeeSalaryHistory'));

                    $salaryHistoryItem = new EmployeeSalaryHistory();
                    $salaryHistoryItem->setTotalEarning(number_format($row['totalEarning'],2));
                    $salaryHistoryItem->setTotalDeduction(number_format($row['totalDeduction'],2));
                    $salaryHistoryItem->setTotalNetsalary(number_format($row['netSalary'],2));
                    $salaryHistoryItem->setMonthlyBasic($row['monthlyBasic']);
                    $salaryHistoryList->add($salaryHistoryItem);

                    $employee->setEmployeeSalaryHistory($salaryHistoryList);

                    $supervisorList = isset($row['supervisors'])?$row['supervisors']:'';

                    if (!empty($supervisorList)) {

                        $supervisors = new Doctrine_Collection(Doctrine::getTable('Employee'));

                        $supervisorArray = explode(',', $supervisorList);
                        foreach ($supervisorArray as $supervisor) {
                            list($first, $middle, $last,$id) = explode('##', $supervisor);
                            $supervisor = new Employee();
                            $supervisor->setFirstName($first);
                            $supervisor->setMiddleName($middle);
                            $supervisor->setLastName($last);
                            $supervisor->setEmpNumber($id);
                            $employee->supervisors[] = $supervisor;
                        }
                    }

                    $locationList = $row['locationIds'];

                    if (!empty($locationList)) {

                        //                    $locations = new Doctrine_Collection(Doctrine::getTable('EmpLocations'));

                        $locationArray = explode(',', $locationList);
                        foreach ($locationArray as $location) {
                            list($id, $name) = explode('##', $location);
                            $empLocation = new Location();
                            $empLocation->setId($id);
                            $empLocation->setName($name);
                            $employee->locations[] = $empLocation;
                        }
                    }

                    $employees[] = $employee;
                }
            }
        }
        else {
            return $statement->fetchAll();
        }
        return $employees;

    }
}