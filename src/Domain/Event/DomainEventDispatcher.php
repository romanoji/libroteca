<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Event;

interface DomainEventDispatcher
{
    /**
     * @param DomainEventListener $eventListener
     * @param string $eventClass
     */
    public function subscribeEventTo(DomainEventListener $eventListener, string $eventClass): void;

    /**
     * @param DomainEvent $event
     * @return mixed
     */
    public function handle(DomainEvent $event): void;
}
