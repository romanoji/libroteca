<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationSender;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationType;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotifierProvider;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderRepository;

class SendNotificationToReaderHandler implements CommandHandler
{
    /** @var ReaderRepository */
    private $readerRepository;

    /** @var NotifierProvider */
    private $notifierProvider;

    /** @var NotificationSender */
    private $notificationSender;

    /**
     * @param ReaderRepository $readerRepository
     * @param NotifierProvider $notifierProvider
     * @param NotificationSender $notificationSender
     */
    public function __construct(
        ReaderRepository $readerRepository,
        NotifierProvider $notifierProvider,
        NotificationSender $notificationSender
    ) {
        $this->readerRepository = $readerRepository;
        $this->notifierProvider = $notifierProvider;
        $this->notificationSender = $notificationSender;
    }

    /**
     * @param SendNotificationToReader $command
     * @throws ReaderNotFoundException
     * @throws \RuntimeException
     */
    public function execute(SendNotificationToReader $command)
    {
        $reader = $this->readerRepository->get(new ReaderID($command->readerID));
        $notificationType = new NotificationType($command->notificationType);

        $notifier = $this->notifierProvider->provideFor($reader, $notificationType);
        $this->notificationSender
            ->setNotifier($notifier)
            ->send($reader, $notificationType);
    }
}
