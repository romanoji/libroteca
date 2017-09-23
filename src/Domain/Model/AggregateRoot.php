<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model;

use RJozwiak\Libroteca\Domain\Event\DomainEvent;

abstract class AggregateRoot
{
    /** @var int */
    private $version = 0;

    /** @var null|DomainEvent[] */
    private $events;

    /**
     * @return ID
     */
    abstract public function id();

    // TODO: handle concurrent saves

    /**
     * @return int
     */
    public function version(): int
    {
        return $this->version;
    }

    public function incrementVersion(): void
    {
        $this->version++;
    }

    /**
     * @return DomainEvent[]
     */
    public function unpublishedEvents(bool $wipeEvents = true): array
    {
        $events = $this->events;

        if ($wipeEvents) {
            $this->events = null;
        }

        return $events ?: [];
    }

    /**
     * @param DomainEvent $event
     */
    protected function addEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }
}
