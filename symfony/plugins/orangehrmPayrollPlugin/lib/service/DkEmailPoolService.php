<?php


class DkEmailPoolService
{
    protected $emailPoolDao;
    protected $configDao;

    /**
     * @return DKEmailPoolDao
     */
    public function getEmailPoolDao()
    {
        if(is_null($this->emailPoolDao)){
            $this->emailPoolDao = new DKEmailPoolDao();
        }
        return $this->emailPoolDao;
    }

    /**
     * @param $emailPoolDao
     */
    public function setEmailPoolDao($emailPoolDao)
    {
        $this->emailPoolDao = $emailPoolDao;
    }


    public function saveMakePaymentNotification($salaryHistoryList,$month,$year){

        $logger = Logger::getLogger('make_payment_email');
        $emailPoolCollection = new Doctrine_Collection('EmailPool');
        foreach ($salaryHistoryList as $salaryHistoryItem){

            $to = $salaryHistoryItem->getEmployee()->getEmpWorkEmail();

            if(!empty($to)){
                $emailPoolitem = new EmailPool();


//                $payslipUrl = $this->getConfigDao()->getValue('domain.name')."/admin/viewEmployeePayslip?empNumber=" . $salaryHistoryItem->getEmployee()->getEmpNumber()
//                    . "&id=" . $salaryHistoryItem->getId()."&mode=view";

                $payslipUrl = $this->getConfigDao()->getValue('domain.name');

                $startDate = $year.'-'.$month.'-01';
                $bodyTemplate = file_get_contents(sfConfig::get('sf_root_dir') . "/plugins/orangehrmPayrollPlugin/modules/admin/templates/mail/en_US/makePaymentBody.txt");
                $bodyReplacementKeys = array('/#employeeFullName/', '/#month/', '/#year/','/#link/');
                $bodyReplacementValues = array($salaryHistoryItem->getEmployee()->getFullName(),date("F", strtotime($startDate)),$year,$payslipUrl);



                $subjectTemplate = file_get_contents(sfConfig::get('sf_root_dir') . "/plugins/orangehrmPayrollPlugin/modules/admin/templates/mail/en_US/makePaymentSubject.txt");
                $subjectReplacementKeys = array( '/#month/', '/#year/');
                $subjectReplacementValues = array(date("F", strtotime($startDate)),$year);


                $emailPoolitem->setToAddress($to);
                $emailPoolitem->setFromAddress(sfConfig::get('app_system_finace_email'));
                $emailPoolitem->setSubject(preg_replace($subjectReplacementKeys, $subjectReplacementValues, $subjectTemplate));
                $emailPoolitem->setBody(preg_replace($bodyReplacementKeys, $bodyReplacementValues, $bodyTemplate));
                $emailPoolitem->setStatus(EmailPool::STATUS_PENDING);
                $emailPoolitem->setContentType(EmailPool::CONTENT_TYPE_HTML);

                $emailPoolCollection->add($emailPoolitem);

            }
            else{
                $logger->error('Work Email is not defined for '.$salaryHistoryItem->getEmployee()->getFullName());
            }


        }

        if(count($emailPoolCollection)>0){
            try{
                $emailPoolCollection->save();
            }
            catch (Exception $e){
                $logger->error($e->getMessage());
            }
        }

    }

    /**
     * @param $email
     * @return mixed
     * @throws DaoException
     */
    public function saveEmail($email){
       return $this->getEmailPoolDao()->saveEmail($email);
    }

    /**
     * @param array $status
     * @return Doctrine_Collection
     * @throws DaoException
     */
    public function searchEmailPoolByStatus($status=array()){
        return $this->getEmailPoolDao()->searchEmailPoolByStatus($status);
    }

    public function getConfigDao(){
        if(is_null($this->configDao)){
            $this->configDao = new ConfigDao();
        }

        return $this->configDao;
    }
}