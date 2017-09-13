<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification;

use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationTemplateBuilder;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationTemplateBuilderFactory as BaseNotificationTemplateProviderFactory;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\Adapter\SimpleEmailNotifier;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\Adapter\SwiftMailerNotifier;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\EmailNotificationTemplateBuilder;

class NotificationTemplateBuilderFactory implements BaseNotificationTemplateProviderFactory
{
    /**
     * @param string $notifierClass
     * @return NotificationTemplateBuilder
     * @throws \InvalidArgumentException
     */
    public function createForNotifier(string $notifierClass): NotificationTemplateBuilder
    {
        switch ($notifierClass) {
            case SimpleEmailNotifier::class:
            case SwiftMailerNotifier::class:
                return new EmailNotificationTemplateBuilder();
            default:
                throw new \InvalidArgumentException('Invalid notifier!');
        }
    }
}
