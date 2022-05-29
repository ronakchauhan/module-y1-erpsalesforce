<?php

namespace Y1\ERPSalesForce\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 *  COnfig
 */
class Config
{
    /** Order Status Config  */
    const XML_PATH_STATUS_PENDING = "erp_order_sync/status/pending";
    const XML_PATH_STATUS_EXPORTED = "erp_order_sync/status/exported";
    const XML_PATH_STATUS_FAILED = "erp_order_sync/status/failed";

    protected ScopeConfigInterface $scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param $store
     *
     * @return mixed
     */
    public function getPendingStatus($store = null)
    {
        return  $this->scopeConfig->getValue(
            self::XML_PATH_STATUS_PENDING,
            ScopeInterface::SCOPE_WEBSITE,
            $store
        );
    }

    /**
     * @param $store
     *
     * @return mixed
     */
    public function getExportedStatus($store = null)
    {
        return  $this->scopeConfig->getValue(
            self::XML_PATH_STATUS_EXPORTED,
            ScopeInterface::SCOPE_WEBSITE,
            $store
        );
    }

    /**
     * @param $store
     *
     * @return mixed
     */
    public function getFailedStatus($store = null)
    {
        return  $this->scopeConfig->getValue(
            self::XML_PATH_STATUS_FAILED,
            ScopeInterface::SCOPE_WEBSITE,
            $store
        );
    }
}
