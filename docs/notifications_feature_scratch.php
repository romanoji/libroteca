<?php
declare(strict_types=1);

namespace Scratch;

// TODO: add created_at dates for BookCopy & Reader
// TODO: add started_at date for BookLoan

interface NotificationReceiver // implementations: Reader, extensions: EmailNotificationReceiver
{
    // TODO: overall implementation

    // TODO: subscribe/unsubscribe methods for each notifications types
}

interface Notification // implementations: EmailNotification
{
}

interface NotificationTemplate // implementations: EndingLoansNotificationTemplate, NewBooksNotificationTemplate
{
    public function template(): string;
    public function placeholders(): array;
}

interface NotificationTemplateProvider // implementations: FromFile, Cached
{
    public function provideBy(NotificationType $type, NotificationMedium $medium): NotificationTemplate;
}

interface NotificationType // EndingLoans, NewBooks
{
    public function type(): string;
}

interface NotificationMedium // Email, SMS, etc.
{

}

interface NotificationFactory
{
    public function createFromTemplate(NotificationTemplate $template, array $values): Notification;
}

interface NotificationSenderFactory // implementations: PreferenceBasedNotificationSenderFactory
{
    public function create(NotificationMedium $medium): NotificationSender;
}

interface NotificationSender // extensions: EmailNotificationSender
{
    public function isTypeSupported(NotificationType $type): bool;

//    public function isMediumSupported(NotificationMedium $medium): bool;

    public function send(NotificationReceiver $receiver, Notification $notification);
}
