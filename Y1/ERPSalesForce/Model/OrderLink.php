<?php

namespace Y1\ERPSalesForce\Model;

use Magento\Framework\Model\AbstractModel;
use Y1\ERPSalesForce\Api\Data\OrderLinkInterface;

/**
 * OrderLink Model
 */
class OrderLink extends AbstractModel implements OrderLinkInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\OrderLink::class);
    }

    /**
     * @inheritdoc
     */
    public function setOrderReferenceId(string $externalOrderReferenceId)
    {
        return $this->setData(self::XML_PATH_ORDER_REFERENCE_ID, $externalOrderReferenceId);
    }

    /**
     * @inheritdoc
     */
    public function getOrderReferenceId()
    {
        $this->getData(self::XML_PATH_ORDER_REFERENCE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::XML_PATH_ORDER_ID, $orderId);
    }

    /**
     * @inheritdoc
     */
    public function getOrderId()
    {
        $this->getData(self::XML_PATH_ORDER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setEmail($email)
    {
        return $this->setData(self::XML_PATH_EMAIL, $email);
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        $this->getData(self::XML_PATH_EMAIL);
    }

    /**
     * @inheritdoc
     */
    public function setIncrementId($incrementId)
    {
        return $this->setData(self::XML_PATH_INCREMENT_ID, $incrementId);
    }

    /**
     * @inheritdoc
     */
    public function getIncrementId()
    {
        $this->getData(self::XML_PATH_INCREMENT_ID);
    }
}
