<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen\Specification\Reader;

use RJozwiak\Libroteca\Application\Query\Specification\EqualsSpecification;

class PhoneEqualsSpecification extends EqualsSpecification
{
    public function __construct(string $value)
    {
        parent::__construct('phone', $value);
    }
}
