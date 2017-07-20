<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model;

interface DomainEvent
{
    /**
     * @return \DateTimeImmutable
     */
    public function occuredOn() : \DateTimeImmutable;
}
