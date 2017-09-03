<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Specification;

class SimpleExpression implements Expression
{
    /** @var string */
    protected $expr;

    /**
     * @param string $expr
     */
    public function __construct(string $expr)
    {
        $this->expr = $expr;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->expr;
    }
}
