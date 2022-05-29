<?php

namespace Y1\ERPSalesForce\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Y1\ERPSalesForce\Model\ResourceModel\OrderLink\CollectionFactory as OrderLinkCollectionFactory;

/**
 * OrderService class
 */
class OrderService
{
    /** Transmission Status Code */
    const SUCCESS_STATUS_CODE = 200;
    const FAILED_STATUS_CODE = [400, 500];

    protected OrderRepositoryInterface $orderRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected Config $config;
    protected CollectionFactory $collectionFactory;
    protected OrderLinkCollectionFactory $orderLinkFactory;

    /**
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Y1\ERPSalesForce\Model\Config $config
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory
     * @param \Y1\ERPSalesForce\Model\ResourceModel\OrderLink\CollectionFactory $orderLinkFactory
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Config $config,
        CollectionFactory $collectionFactory,
        OrderLinkCollectionFactory $orderLinkFactory
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->config = $config;
        $this->collectionFactory = $collectionFactory;
        $this->orderLinkFactory = $orderLinkFactory;
    }

    /**
     * @return array
     */
    public function getOrdersToExport()
    {
        return $this->queryOrdersToExport();
    }

    /**
     * @return array
     */
    private function queryOrdersToExport()
    {
        $statusToMatch = [$this->config->getPendingStatus()];

        return $this->collectionFactory->create()
            ->addFieldToSelect('order_reference_id')
            ->addFieldToFilter('order_reference_id', ['eq' => "0"])
            ->addFieldToFilter(Order::STATUS,['in' => $statusToMatch])
            ->getAllIds();
    }

    /**
     * @param $status
     *
     * @return void|\Y1\ERPSalesForce\Model\ResourceModel\OrderLink\Collection
     */
    public function getTransmission($status)
    {
        if ($status === 'success') {
            return $this->orderLinkFactory->create()
                ->addFieldToFilter("erp_response", self::SUCCESS_STATUS_CODE)
                ->setPageSize(10);
        } elseif ($status === 'failed') {
            return $this->orderLinkFactory->create()
                ->addFieldToFilter("erp_response", ["in" => self::FAILED_STATUS_CODE])
                ->setPageSize(10);
        }
    }
}
