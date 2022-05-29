<?php

namespace Y1\ERPSalesForce\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * OrderLink ResourceModel
 */
class OrderLink extends AbstractDb
{
    /** Table Name */
    const TABLE_NAME = "y1_erp_sync_order";

    protected function _construct()
    {
        return $this->_init(static::TABLE_NAME, "entity_id");
    }
}
