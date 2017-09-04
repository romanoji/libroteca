<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen\Specification;

use RJozwiak\Libroteca\Application\Query\Specification\Expression;

class QueryBuilderExpression implements Expression
{
    /** @var \Closure */
    protected $expr;

    /**
     * @param \Closure $expr
     */
    public function __construct(\Closure $expr)
    {
        $this->expr = $expr;
    }

    /**
     * @return \Closure
     */
    public function value(): \Closure
    {
        return $this->expr;
    }
}
