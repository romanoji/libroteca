<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader\Notification;

interface NotificationBuilderFactory
{
    /**
     * @param string $notifierClass
     * @return NotificationBuilder
     */
    public function createForNotifier(string $notifierClass): NotificationBuilder;
}
