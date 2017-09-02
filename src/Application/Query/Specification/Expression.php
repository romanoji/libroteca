<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Specification;

interface Expression
{
    /**
     * @return mixed
     */
    public function value();
}
