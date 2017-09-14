<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Event;

interface DomainEvent
{
    /**
     * @return \DateTimeImmutable
     */
    public function occuredOn(): \DateTimeImmutable;
}
