<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader\Notification;

interface NotificationTemplateBuilder
{
    /**
     * @param NotificationType $type
     * @return NotificationTemplate
     */
    public function provideFor(NotificationType $type);
}
