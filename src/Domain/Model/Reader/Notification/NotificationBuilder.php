<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader\Notification;

use RJozwiak\Libroteca\Domain\Model\Reader\Reader;

interface NotificationBuilder
{
    /**
     * @param NotificationTemplate $notificationTemplate
     * @param Reader $reader
     * @return Notification
     */
    public function create($notificationTemplate, Reader $reader);
}
