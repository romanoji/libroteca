<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader\Notification;

interface NotificationTemplateBuilderFactory
{
    /**
     * @param string $notifierClass
     * @return NotificationTemplateBuilder
     */
    public function createForNotifier(string $notifierClass): NotificationTemplateBuilder;
}
