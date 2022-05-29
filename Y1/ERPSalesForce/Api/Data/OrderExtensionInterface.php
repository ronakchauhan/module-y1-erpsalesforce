<?php

namespace Y1\ERPSalesForce\Api\Data;

use Magento\Framework\Api\ExtensionAttributesInterface;

/**
 * Order Extension Interface
 */
interface OrderExtensionInterface extends ExtensionAttributesInterface
{

    /**
     * @return string|null
     */
    public function getOrderReferenceId();

    /**
     * @param string|null $orderReferenceId
     *
     * @return $this
     */
    public function setOrderReferenceId(string $orderReferenceId);
}
