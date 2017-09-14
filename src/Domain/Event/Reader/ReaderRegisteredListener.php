<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Event\Reader;

use RJozwiak\Libroteca\Domain\Event\DomainEventListener;
use RJozwiak\Libroteca\Domain\Model\Reader\{
    Exception\ReaderNotFoundException, Notification\NotifierProvider, Notification\NotificationSender,
    Notification\NotificationType, ReaderRegistered, ReaderRepository, ReaderID
};

class ReaderRegisteredListener implements DomainEventListener
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
     * @param ReaderRegistered $event
     * @throws ReaderNotFoundException
     * @throws \RuntimeException
     */
    public function handle($event): void
    {
        $reader = $this->readerRepository->get($event->readerID());

        $notifier = $this->notifierProvider->provideFor($reader, NotificationType::WELCOME());
        $this->notificationSender
            ->setNotifier($notifier)
            ->send($reader, NotificationType::WELCOME());
    }
}
