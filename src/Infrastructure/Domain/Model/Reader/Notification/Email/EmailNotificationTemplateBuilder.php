<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email;

use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationTemplateBuilder;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationType;

class EmailNotificationTemplateBuilder implements NotificationTemplateBuilder
{
    /**
     * @param NotificationType $type
     * @return EmailNotificationTemplate
     * @throws \InvalidArgumentException
     */
    public function provideFor(NotificationType $type)
    {
        switch ($type) {
            case NotificationType::WELCOME():
                return $this->welcomeTemplate($type);
            case NotificationType::ENDING_LOANS(): // TODO:
            case NotificationType::NEW_BOOKS(): // TODO:
            default:
                throw new \InvalidArgumentException('Invalid notification type!');
        }
    }

    /**
     * @param NotificationType $type
     * @return EmailNotificationTemplate
     */
    private function welcomeTemplate(NotificationType $type): EmailNotificationTemplate
    {
        // TODO: move to separate file

        $subject = 'Welcome to Libroteca!';
        $message = <<<MSG
<div>
    <h3>Welcome {{name}}!</h3>
</div>
MSG;

        return new EmailNotificationTemplate(
            $type,
            $message,
            $subject
        );
    }
}
