<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\SMS;

use RJozwiak\Libroteca\Domain\Model\Reader\Notification\Notification;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationType;
use RJozwiak\Libroteca\Domain\Model\Reader\Phone;

class SMSNotification extends Notification
{
    /**
     * @var Phone
     */
    private $to;

    /**
     * @var string
     */
    private $message;

    /**
     * @param NotificationType $type
     * @param Phone $to
     * @param string $message
     */
    public function __construct(NotificationType $type, Phone $to, string $message)
    {
        parent::__construct($type);

        $this->to = $to;
        $this->message = $message;
    }

    /**
     * @return Phone
     */
    public function to(): Phone
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
