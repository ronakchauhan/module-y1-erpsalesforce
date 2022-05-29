<?php
declare(strict_types=1);

namespace Y1\ERPSalesForce\Model;

use Exception;
use Illuminate\Support\Arr;
use Magento\AsynchronousOperations\Api\Data\OperationInterface;
use Magento\Framework\Bulk\OperationManagementInterface;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use Y1\ERPSalesForce\Model\OrderLinkFactory;

class SyncOrderConsumer
{
    protected LoggerInterface $logger;
    protected SerializerInterface $serializer;
    protected OperationManagementInterface $operationManagement;
    protected EntityManager $entityManager;
    protected OrderLinkFactory $orderLinkFactory;
    protected OrderRepositoryInterface $orderRepository;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Magento\Framework\Bulk\OperationManagementInterface $operationManagement
     * @param \Magento\Framework\EntityManager\EntityManager $entityManager
     * @param \Y1\ERPSalesForce\Model\OrderLinkFactory $orderLinkFactory
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        LoggerInterface $logger,
        SerializerInterface $serializer,
        OperationManagementInterface $operationManagement,
        EntityManager $entityManager,
        OrderLinkFactory $orderLinkFactory,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->operationManagement = $operationManagement;
        $this->entityManager = $entityManager;
        $this->orderLinkFactory = $orderLinkFactory;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param \Magento\AsynchronousOperations\Api\Data\OperationInterface $operation
     *
     * @return void
     * @throws \Exception
     */
    public function process(OperationInterface $operation)
    {
        try {
            $serializedData = $operation->getSerializedData();
            $data = $this->serializer->unserialize($serializedData);
            $this->execute($data);
        } catch (LocalizedException $e) {
            $this->logger->critical($e->getMessage());
            $status = OperationInterface::STATUS_TYPE_NOT_RETRIABLY_FAILED;
            $errorCode = $e->getCode();
            $message = $e->getMessage();
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
            $status = OperationInterface::STATUS_TYPE_NOT_RETRIABLY_FAILED;
            $errorCode = $e->getCode();
            $message =
                __("Sorry, Something went wrong during product attribute update, Please see log for more information");
        }

        $operation->setStatus($status ?? OperationInterface::STATUS_TYPE_COMPLETE)
            ->setErrorCode($errorCode ?? null)
            ->setResultMessage($message ?? null);

        $this->entityManager->save($operation);
    }

    /**
     * @param $data
     *
     * @return void
     * @throws \Exception
     */
    private function execute($data): void
    {
        $orders = $data['data'];

        foreach ($orders as $orderId) {
            /** @var \Magento\Sales\Api\Data\OrderInterface $order */
            $order = $this->orderRepository->get($orderId);
            $order->setData("order_reference_id", $order->getIncrementId());
            $this->orderRepository->save($order);

            //TODO: Export logic API

            // Static response from EPR as 200/400/500
            $response = [200, 400, 500];

            /** @var \Y1\ERPSalesForce\Model\OrderLink $orderLink */
            $orderLink = $this->orderLinkFactory->create();
            $orderLink->setOrderId($orderId);
            $orderLink->setEmail($order->getCustomerEmail());
            $orderLink->setOrderQty($order->getTotalQtyOrdered());
            $orderLink->setOrderReferenceId($order->getIncrementId());
            $orderLink->setErpResponse(Arr::random($response));
            $orderLink->setIncrementId($order->getIncrementId());
            $orderLink->save();

            $this->logger->info("Export Order to ERP: ");
            $this->logger->info($orderLink->getData());
        }
    }
}
