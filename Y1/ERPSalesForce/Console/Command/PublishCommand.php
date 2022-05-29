<?php

namespace Y1\ERPSalesForce\Console\Command;

use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Y1\ERPSalesForce\Model\SyncOrderPublisher;

/**
 *  Command to publish ERP message queue
 */
class PublishCommand extends Command
{
    protected State $state;
    protected SyncOrderPublisher $syncOrderPublisher;

    /**
     * @param \Magento\Framework\App\State $state
     * @param \Y1\ERPSalesForce\Model\SyncOrderPublisher $syncOrderPublisher
     * @param string|null $name
     */
    public function __construct(State $state, SyncOrderPublisher $syncOrderPublisher, string $name = null)
    {
        parent::__construct($name);
        $this->state = $state;
        $this->syncOrderPublisher = $syncOrderPublisher;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName("sync:order:publish")
            ->setDescription("Publish a new order.");
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

        $result = $this->syncOrderPublisher->publish();
        $output->writeln('<info>' . $result . '</info>');

        return Cli::RETURN_SUCCESS;
    }
}
