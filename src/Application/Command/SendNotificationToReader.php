<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class SendNotificationToReader implements Command
{
    /** @var int|string */
    public $readerID;

    /** @var int|string */
    public $notificationType;

    /**
     * @param int|string $readerID
     * @param int|string $notificationType
     */
    public function __construct($readerID, $notificationType)
    {
        $this->readerID = $readerID;
        $this->notificationType = $notificationType;
    }
}
