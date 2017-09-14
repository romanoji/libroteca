<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Event;

interface DomainEventBus
{
    /**
     * @param DomainEventListener $eventListener
     */
    public function subscribe(DomainEventListener $eventListener): void;

    /**
     * @param DomainEvent $event
     * @return mixed
     */
    public function handle(DomainEvent $event): void;
}
