<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email;

use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationTemplate;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationType;

class EmailNotificationTemplate extends NotificationTemplate
{
    /** @var string */
    private $subject;

    /**
     * @param NotificationType $notificationType
     * @param string $template
     * @param string $subject
     */
    public function __construct(
        NotificationType $notificationType,
        string $template,
        string $subject
    ) {
        parent::__construct($notificationType, $template);

        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function subject()
    {
        return $this->subject;
    }
}
