<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification;

use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationBuilder;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationBuilderFactory as BaseNotificationBuilderFactory;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\Adapter\SimpleEmailNotifier;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\Adapter\SwiftMailerNotifier;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\Adapter\TwigEmailNotificationBuilder;

class NotificationBuilderFactory implements BaseNotificationBuilderFactory
{
    /**
     * @param string $notifierClass
     * @return NotificationBuilder
     * @throws \InvalidArgumentException
     */
    public function createForNotifier(string $notifierClass): NotificationBuilder
    {
        switch ($notifierClass) {
            case SimpleEmailNotifier::class:
            case SwiftMailerNotifier::class:
                return new TwigEmailNotificationBuilder();
            default:
                throw new \InvalidArgumentException('Invalid notifier!');
        }
    }
}
