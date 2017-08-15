<?php

namespace RJozwiak\Libroteca\Lumen\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'RJozwiak\Libroteca\Lumen\Events\SomeEvent' => [
            'RJozwiak\Libroteca\Lumen\Listeners\EventListener',
        ],
    ];
}
