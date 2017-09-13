<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader\Notification;

use RJozwiak\Libroteca\Domain\Model\Reader\Reader;

class NotificationSender
{
    /** @var NotificationTemplateBuilderFactory */
    protected $notificationTemplateBuilderFactory;

    /** @var NotificationBuilderFactory */
    protected $notificationBuilderFactory;

    /** @var Notifier */
    protected $notifier;

    /** @var NotificationTemplateBuilder */
    protected $notificationTemplateBuilder;

    /** @var NotificationBuilder */
    protected $notificationBuilder;

    /**
     * @param NotificationTemplateBuilderFactory $templateProviderFactory
     * @param NotificationBuilderFactory $notificationBuilderFactory
     */
    public function __construct(
        NotificationTemplateBuilderFactory $templateProviderFactory,
        NotificationBuilderFactory $notificationBuilderFactory
    ) {
        $this->notificationTemplateBuilderFactory = $templateProviderFactory;
        $this->notificationBuilderFactory = $notificationBuilderFactory;
    }

    /**
     * @param Reader $reader
     * @param NotificationType $notificationType
     * @throws \RuntimeException
     */
    public function send(Reader $reader, NotificationType $notificationType): void
    {
        if ($this->notifier === null) {
            throw new \RuntimeException('Notifier is not defined!');
        }

        $notificationTemplate = $this->notificationTemplateBuilder->provideFor($notificationType);
        $notification = $this->notificationBuilder->create($notificationTemplate, $reader);

        $this->notifier->send($notification);
    }

    /**
     * @param Notifier $notifier
     * @return NotificationSender
     */
    public function setNotifier(Notifier $notifier): NotificationSender
    {
        $this->notifier = $notifier;
        $this->setTemplateProviderByNotifier($notifier);
        $this->setNotificationFactoryByNotifier($notifier);

        return $this;
    }

    /**
     * @param Notifier $notifier
     */
    private function setTemplateProviderByNotifier(Notifier $notifier): void
    {
        $this->notificationTemplateBuilder =
            $this->notificationTemplateBuilderFactory->createForNotifier(get_class($notifier));
    }

    /**
     * @param Notifier $notifier
     */
    private function setNotificationFactoryByNotifier(Notifier $notifier): void
    {
        $this->notificationBuilder = $this->notificationBuilderFactory->createForNotifier(get_class($notifier));
    }
}
