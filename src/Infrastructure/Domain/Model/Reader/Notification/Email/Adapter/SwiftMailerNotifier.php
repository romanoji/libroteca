<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\Adapter;

use RJozwiak\Libroteca\Domain\Model\Reader\Email;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\Notifier;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\EmailNotification;

class SwiftMailerNotifier implements Notifier
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var Email */
    private $senderEmail;

    /**
     * @param \Swift_Mailer $mailer
     * @param Email $senderEmail
     */
    public function __construct(\Swift_Mailer $mailer, Email $senderEmail)
    {
        $this->mailer = $mailer;
        $this->senderEmail = $senderEmail;
    }

    /**
     * @param EmailNotification $notification
     * @throws \RuntimeException
     */
    public function send($notification): void
    {
        $message = (new \Swift_Message())
            ->setFrom($this->senderEmail->email())
            ->setTo($notification->to()->email())
            ->setSubject($notification->subject())
            ->setBody($notification->message(), 'text/html');

        $this->mailer->send($message);
    }
}
