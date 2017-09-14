<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Event;

use RJozwiak\Libroteca\Domain\Event\DomainEvent;
use RJozwiak\Libroteca\Domain\Event\DomainEventDispatcher;
use RJozwiak\Libroteca\Domain\Event\DomainEventListener;

class SimpleEventDispatcher implements DomainEventDispatcher
{
    /** @var DomainEventListener[][] */
    private $eventsListeners = [];

    /**
     * @param DomainEventListener $eventListener
     * @param string $eventClass
     */
    public function subscribeEventTo(DomainEventListener $eventListener, string $eventClass): void
    {
        if (!isset($this->eventsListeners[$eventClass])) {
            $this->eventsListeners[$eventClass] = [];
        }

        $this->eventsListeners[$eventClass][] = $eventListener;
    }

    /**
     * @param DomainEvent $event
     * @return mixed
     */
    public function handle(DomainEvent $event): void
    {
        $eventClass = get_class($event);

        if (isset($this->eventsListeners[$eventClass])) {
            foreach ($this->eventsListeners[$eventClass] as $eventListener) {
                $eventListener->handle($event);
            }
        }
    }
}
