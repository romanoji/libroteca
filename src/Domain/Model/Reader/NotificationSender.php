<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader;

interface NotificationSender
{
    /**
     * @param ReaderID $readerID
     * @param Notification $notification
     */
    public function send(ReaderID $readerID, Notification $notification) : void;
}
