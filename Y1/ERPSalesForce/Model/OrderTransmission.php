<?php

namespace Y1\ERPSalesForce\Model;

use Magento\Framework\Serialize\SerializerInterface;
use OrderTransmissionInterface;

class OrderTransmission implements OrderTransmissionInterface
{
    private OrderService $orderService;
    private SerializerInterface $serializer;

    /**
     * @param \Y1\ERPSalesForce\Model\OrderService $orderService
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     */
    public function __construct(OrderService $orderService, SerializerInterface $serializer)
    {
        $this->orderService = $orderService;
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function getTransmissionOrderStatus(string $status): array
    {
        $statusData = $this->orderService->getTransmission($status);
        $result = [];

        if (count($statusData) > 0) {
            foreach ($statusData->getItems() as $item) {
                $result[] = $this->serializer->serialize($item->getData());
            }
        }

        return $result;
    }
}
