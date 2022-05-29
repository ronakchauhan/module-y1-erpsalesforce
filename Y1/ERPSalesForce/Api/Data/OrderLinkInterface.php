<?php

namespace Y1\ERPSalesForce\Api\Data;

/**
 * @api
 */
interface OrderLinkInterface
{
    const XML_PATH_ORDER_REFERENCE_ID = "order_reference_id";
    const XML_PATH_ORDER_ID = "order_id";
    const XML_PATH_EMAIL = "email";
    const XML_PATH_INCREMENT_ID = "increment_id";

    /**
     * Set External Order Reference ID
     *
     * @param string $externalOrderReferenceId
     *
     * @return $this
     */
    public function setOrderReferenceId(string $externalOrderReferenceId);

    /**
     * Get External Order Reference Id
     *
     * @return string
     */
    public function getOrderReferenceId();

    /**
     * Set Order Id
     *
     * @param $orderId
     *
     * @return $this
     */
    public function setOrderId($orderId);

    /**
     * Get Order Id
     *
     * @return $this
     */
    public function getOrderId();

    /**
     * Set Customer Email ID
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email);

    /**
     * Get Customer Email
     *
     * @return $this
     */
    public function getEmail();

    /**
     * Set Increment Id
     *
     * @param $incrementId
     *
     * @return $this
     */
    public function setIncrementId($incrementId);

    /**
     * Get Increment Id
     *
     * @return $this
     */
    public function getIncrementId();

    /**
     * @return $this
     * @throws \Exception
     */
    public function save();

    /**
     * @param integer $modelId
     * @param null|string $field
     *
     * @return $this
     */
    public function load($modelId, $field = null);
}
