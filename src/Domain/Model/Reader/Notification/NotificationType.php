<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader\Notification;

use MyCLabs\Enum\Enum;

/**
 * @method static NotificationType WELCOME()
 * @method static NotificationType ENDING_LOANS()
 * @method static NotificationType NEW_BOOKS()
 */
class NotificationType extends Enum
{
    public const WELCOME = 0;
    public const ENDING_LOANS = 1;
    public const NEW_BOOKS = 2;
}
