<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\Adapter;

use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationBuilder;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationType;
use RJozwiak\Libroteca\Domain\Model\Reader\Reader;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\EmailNotification;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\EmailNotificationTemplate;

class TwigEmailNotificationBuilder implements NotificationBuilder
{
    /**
     * @param EmailNotificationTemplate $notificationTemplate
     * @param Reader $reader
     * @return EmailNotification
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function create($notificationTemplate, Reader $reader): EmailNotification
    {
        $message = $this->compileTemplate($notificationTemplate, $reader);

        return new EmailNotification(
            $notificationTemplate->notificationType(),
            $reader->email(),
            $notificationTemplate->subject(),
            $message
        );
    }

    /**
     * @param EmailNotificationTemplate $notificationTemplate
     * @param Reader $reader
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function compileTemplate(EmailNotificationTemplate $notificationTemplate, Reader $reader)
    {
        $loader = new \Twig_Loader_Array(['notification' => $notificationTemplate->template()]);
        $twig = new \Twig_Environment($loader);

        $values = $this->valuesByNotificationType($notificationTemplate->notificationType(), $reader);

        return $twig->render('notification', $values);
    }

    /**
     * @param NotificationType $type
     * @param Reader $reader
     * @return array
     */
    private function valuesByNotificationType(NotificationType $type, Reader $reader): array
    {
        switch ($type) {
            case NotificationType::WELCOME():
                return ['name' => (string) $reader->name()];
            case NotificationType::ENDING_LOANS(): // TODO:
            case NotificationType::NEW_BOOKS(): // TODO:
            default:
                return [];
        }
    }
}
