<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader\Notification;

use RJozwiak\Libroteca\Domain\Model\Reader\Reader;

interface NotifierProvider
{
    /**
     * @param Reader $reader
     * @param NotificationType $notificationType
     * @return Notifier
     */
    public function provideFor(Reader $reader, NotificationType $notificationType): Notifier;
}
