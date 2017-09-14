<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;
use RJozwiak\Libroteca\Domain\Event\DomainEventDispatcher;
use RJozwiak\Libroteca\Domain\Event\Reader\ReaderRegisteredListener;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderRegistered;
use RJozwiak\Libroteca\Infrastructure\Domain\Event\SimpleEventDispatcher;

class DomainEventsListenersProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DomainEventDispatcher::class, function (Application $app) {
            $eventDispatcher = new SimpleEventDispatcher();

            $eventDispatcher->subscribeEventTo(
                $this->app->make(ReaderRegisteredListener::class),
                ReaderRegistered::class
            );

            return $eventDispatcher;
        });

        $this->app->singleton(ReaderRegisteredListener::class);
    }
}
