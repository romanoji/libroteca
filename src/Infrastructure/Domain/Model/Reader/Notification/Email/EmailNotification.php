<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email;

use RJozwiak\Libroteca\Domain\Model\Reader\Email;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\Notification;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationType;

class EmailNotification extends Notification
{
    /** @var Email */
    private $to;

    /** @var string */
    private $subject;

    /** @var string */
    private $message;

    /**
     * @param NotificationType $type
     * @param Email $to
     * @param string $subject
     * @param string $message
     */
    public function __construct(
        NotificationType $type,
        Email $to,
        string $subject,
        string $message
    ) {
        parent::__construct($type);

        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * @return Email
     */
    public function to(): Email
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
