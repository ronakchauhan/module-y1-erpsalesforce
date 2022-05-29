<?php

namespace Y1\ERPSalesForce\Model;

use Magento\AsynchronousOperations\Api\Data\OperationInterface;
use Magento\AsynchronousOperations\Api\Data\OperationInterfaceFactory;
use Magento\Framework\Bulk\BulkManagementInterface;
use Magento\Framework\DataObject\IdentityGeneratorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;

class SyncOrderPublisher
{
    private BulkManagementInterface $bulkManagement;
    private OperationInterfaceFactory $operationFactory;
    private IdentityGeneratorInterface $identityService;
    private SerializerInterface $serializer;
    private int $bulkSize;
    private OrderService $orderService;

    public function __construct(
        BulkManagementInterface $bulkManagement,
        OperationInterfaceFactory $operationFactory,
        IdentityGeneratorInterface $identityService,
        SerializerInterface $serializer,
        OrderService $orderService,
        int $bulkSize = 100
    )
    {
        $this->bulkManagement = $bulkManagement;
        $this->operationFactory = $operationFactory;
        $this->identityService = $identityService;
        $this->serializer = $serializer;
        $this->bulkSize = $bulkSize;
        $this->orderService = $orderService;
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function publish():void
    {
        $orders = $this->orderService->getOrdersToExport();
        if (count($orders)) {
            $bulkUuid = $this->identityService->generateId();
            $bulkDescription = __("Syncing Orders");
            $operation = $this->makeOperation(
                'Export Order Required',
                'erpsalesforce.sync.order',
                $orders,
                $bulkUuid
            );
            $result = $this->bulkManagement->scheduleBulk(
                $bulkUuid,
                [$operation],
                $bulkDescription
            );

            if (!$result) {
                throw new LocalizedException(
                    __("Something failed creating bulk operation.")
                );
            }
        }
    }

    /**
     * @param $meta
     * @param $queue
     * @param $dataToExport
     * @param $bulkUuid
     *
     * @return \Magento\AsynchronousOperations\Api\Data\OperationInterface
     */
    protected function MakeOperation(
        $meta,
        $queue,
        $dataToExport,
        $bulkUuid
    ): OperationInterface
    {
        $dataToEncode = [
            'meta_information' => $meta,
            'data' => $dataToExport
        ];

        $data = [
            'data' => [
                'bulk_uuid' =>  $bulkUuid,
                'topic_name' => $queue,
                'serialized_data' => $this->serializer->serialize($dataToEncode),
                'status' => \Magento\Framework\Bulk\OperationInterface::STATUS_TYPE_OPEN
            ]
        ];

        return $this->operationFactory->create($data);
    }
}
