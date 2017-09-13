<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader\Notification;

class NotificationTemplate
{
    /** @var NotificationType */
    protected $notificationType;

    /** @var string */
    protected $template;

    /**
     * @param NotificationType $notificationType
     * @param string $template
     */
    public function __construct(NotificationType $notificationType, string $template)
    {
        $this->notificationType = $notificationType;
        $this->template = $template;
    }

    /**
     * @return NotificationType
     */
    public function notificationType(): NotificationType
    {
        return $this->notificationType;
    }

    /**
     * @return string
     */
    public function template(): string
    {
        return $this->template;
    }
}
