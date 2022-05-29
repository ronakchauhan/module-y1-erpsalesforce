<?php
declare(strict_types=1);

namespace Y1\ERPSalesForce\Model\ResourceModel\OrderLink;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Y1\ERPSalesForce\Model\OrderLink;

/**
 *  Transmission Collection
 */
class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(OrderLink::class, \Y1\ERPSalesForce\Model\ResourceModel\OrderLink::class);
    }
}
