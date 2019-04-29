<?php


class DkConfigService extends ConfigService
{
    const KEY_EPF_PERCENTAGE = "payroll.epf_percentage";
    const KEY_ETF_PERCENTAGE = "payroll.etf_percentage";
    const KEY_NOPAY_LEAVE_TYPE_ID = "payroll.nopay_leave_type_id";
    const KEY_NOPAY_LEAVE_DEDUCTION = "payroll.nopay_leave_deduction";

    /**
     * @param $value
     * @throws CoreServiceException
     */
    public function setEpfPercentage($value) {
        $this->_setConfigValue(self::KEY_EPF_PERCENTAGE, $value);
    }

    /**
     * @param $value
     * @throws CoreServiceException
     */
    public function setEtfPercentage($value) {
        $this->_setConfigValue(self::KEY_ETF_PERCENTAGE, $value);
    }

    /**
     * @param $value
     * @throws CoreServiceException
     */
    public function setNopayLeaveTypeId($value) {
        $this->_setConfigValue(self::KEY_NOPAY_LEAVE_TYPE_ID, $value);
    }

    /**
     * @param $value
     * @throws CoreServiceException
     */
    public function setNopayLeaveDeduction($value) {
        $this->_setConfigValue(self::KEY_NOPAY_LEAVE_DEDUCTION, $value);
    }

    /**
     * @return String
     * @throws CoreServiceException
     */
    public function getEpfPercentage() {
        return $this->_getConfigValue(self::KEY_EPF_PERCENTAGE);
    }

    /**
     * @return String
     * @throws CoreServiceException
     */
    public function getEtfPercentage() {
        return $this->_getConfigValue(self::KEY_ETF_PERCENTAGE);
    }

    /**
     * @return String
     * @throws CoreServiceException
     */
    public function getNopayLeaveTypeId() {
        return $this->_getConfigValue(self::KEY_NOPAY_LEAVE_TYPE_ID);
    }

    /**
     * @return String
     * @throws CoreServiceException
     */
    public function getNopayLeaveDeduction() {
        return $this->_getConfigValue(self::KEY_NOPAY_LEAVE_DEDUCTION);
    }
}