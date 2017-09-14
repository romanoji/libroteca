<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;
use RJozwiak\Libroteca\Domain\Model\Email;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationBuilder;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationBuilderFactory as BaseNotificationBuilderFactory;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationSender;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationTemplateBuilder;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationTemplateBuilderFactory as BaseNotificationTemplateBuilderFactory;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\Notifier;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\Adapter\SwiftMailerNotifier;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\Adapter\TwigEmailNotificationBuilder;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\Email\EmailNotificationTemplateBuilder;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\NotificationBuilderFactory;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\Notification\NotificationTemplateBuilderFactory;

class NotificationsServicesProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('APP_EMAIL', function (Application $app) {
            return new Email(env('EMAIL_USERNAME'));
        });

        $this->app->singleton(\Swift_Transport::class, function (Application $app) {
            return (new \Swift_SmtpTransport(
                env('EMAIL_HOST'),
                env('EMAIL_PORT'),
                env('EMAIL_ENCRYPTION')
            ))->setUsername(env('EMAIL_USERNAME'))->setPassword(env('EMAIL_PASSWORD'));
        });

        $this->app->singleton(\Swift_Mailer::class);

        $this->app->singleton(Notifier::class, function (Application $app) {
            return new SwiftMailerNotifier(
                $this->app->make(\Swift_Mailer::class),
                $this->app->make('APP_EMAIL')
            );
        });

        $this->app->singleton(NotificationBuilder::class, TwigEmailNotificationBuilder::class);

        $this->app->singleton(NotificationTemplateBuilder::class, EmailNotificationTemplateBuilder::class);

        $this->app->singleton(BaseNotificationBuilderFactory::class, NotificationBuilderFactory::class);

        $this->app->singleton(BaseNotificationTemplateBuilderFactory::class, NotificationTemplateBuilderFactory::class);

        $this->app->singleton(NotificationSender::class);
    }
}
