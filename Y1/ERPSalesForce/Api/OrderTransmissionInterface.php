<?php

interface OrderTransmissionInterface
{
    /**
     * @api
     *
     * @param \Y1\ERP\Api\Data\ProductStockDataInterface[] $products
     *
     * @return \Y1\ERP\Api\Data\ProductStockDataInterface[]
     */
    public function getTransmissionOrderStatus(
        string $status
    ): array;
}
