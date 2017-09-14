<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Event;

interface DomainEventListener
{
    /**
     * @param DomainEvent $event
     */
    public function handle($event): void;
}
