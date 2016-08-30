<?php

namespace RJozwiak\Libroteca\Domain\Model;

interface DomainEvent
{
    /**
     * @return \DateTimeImmutable
     */
    public function occuredOn();
}
