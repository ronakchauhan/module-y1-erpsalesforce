<?php

namespace Y1\ERPSalesForce\Console\Command;

use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Magento\Framework\Serialize\SerializerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Y1\ERPSalesForce\Model\OrderService;
use Y1\ERPSalesForce\Model\SyncOrderPublisher;

/**
 * Command to fetch Transmission
 */
class TransmissionCommand extends Command
{
    /** Status of transmission */
    const ARGUMENT_STATUS = "status";

    protected State $state;
    protected SyncOrderPublisher $syncOrderPublisher;
    protected OrderService $orderService;
    protected SerializerInterface $serializer;

    /**
     * @param \Magento\Framework\App\State $state
     * @param \Y1\ERPSalesForce\Model\SyncOrderPublisher $syncOrderPublisher
     * @param \Y1\ERPSalesForce\Model\OrderService $orderService
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param string|null $name
     */
    public function __construct(
        State $state,
        SyncOrderPublisher $syncOrderPublisher,
        OrderService $orderService,
        SerializerInterface $serializer,
        string $name = null
    ) {
        parent::__construct($name);
        $this->state = $state;
        $this->syncOrderPublisher = $syncOrderPublisher;
        $this->orderService = $orderService;
        $this->serializer = $serializer;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $options = [
            new InputOption(self::ARGUMENT_STATUS, null, InputOption::VALUE_REQUIRED,
                'Transmission Status (last 10 transmission)')
        ];

        $this->setName("sync:order:transmission")
            ->setDefinition($options)
            ->setDescription("Fetch ERP transmission with status success/failed.");
        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode('adminhtml');
        $result = false;

        if ($status = $input->getOption(self::ARGUMENT_STATUS)) {
            $output->writeln("Last 10 Transmission which are successfully transmitted " . $status);

            $result = $this->orderService->getTransmission($status);
        }

        if (count($result) > 0) {
            foreach ($result->getItems() as $item) {
                //                $output->writeln('<info>' . $item->getEntityId() . '</info>');
                $output->writeln('<info>' . $this->serializer->serialize($item->getData()) . '</info>');
            }
        }

        return Cli::RETURN_SUCCESS;
    }
}
