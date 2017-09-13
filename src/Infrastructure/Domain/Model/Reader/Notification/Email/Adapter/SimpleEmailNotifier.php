<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\Adapter;

use RJozwiak\Libroteca\Domain\Model\Reader\Email;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\Notifier;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\EmailNotification;

class SimpleEmailNotifier implements Notifier
{
    /** @var Email */
    private $from;

    /**
     * @param Email $from
     */
    public function __construct(Email $from)
    {
        $this->from = $from;
    }

    /**
     * @param EmailNotification $notification
     * @throws \RuntimeException
     */
    public function send($notification): void
    {
        \SimpleMail::make()
            ->setFrom($this->from->email(), 'Libroteca')
            ->setTo($notification->to()->email(), '')
            ->setSubject($notification->subject())
            ->setMessage($notification->message())
            ->setHtml()
            ->send();
    }
}
