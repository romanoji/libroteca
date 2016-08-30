<?php

namespace RJozwiak\Libroteca\Domain\Model;

interface AggregateRoot
{
    /** @return ID */
    public function id();
}
