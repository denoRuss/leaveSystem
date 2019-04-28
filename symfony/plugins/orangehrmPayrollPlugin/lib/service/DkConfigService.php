<?php


class DkConfigService extends ConfigService
{
    const KEY_EPF_PERCENTAGE = "payroll.epf_percentage";
    const KEY_ETF_PERCENTAGE = "payroll.etf_percentage";

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
}