<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class SendNotificationToReader implements Command
{
    /** @var int|string */
    private $readerID;

    /** @var int|string */
    private $notificationType;

    /**
     * @param int|string $readerID
     * @param int|string $notificationType
     */
    public function __construct($readerID, $notificationType)
    {
        $this->readerID = $readerID;
        $this->notificationType = $notificationType;
    }

    /**
     * @return int|string
     */
    public function readerID()
    {
        return $this->readerID;
    }

    /**
     * @return int|string
     */
    public function notificationType()
    {
        return $this->notificationType;
    }
}
