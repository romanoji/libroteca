<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader\Notification;

abstract class Notification
{
    /** @var NotificationType */
    private $type;

    /**
     * @param NotificationType $type
     */
    public function __construct(NotificationType $type)
    {
        $this->type = $type;
    }

    /**
     * @return NotificationType
     */
    public function type(): NotificationType
    {
        return $this->type;
    }

    /**
     * @return string
     */
    abstract public function message(): string;
}
