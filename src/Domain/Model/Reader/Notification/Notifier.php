<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader\Notification;

interface Notifier
{
    /**
     * @param Notification $notification
     */
    public function send($notification): void;
}
