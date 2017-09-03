<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Specification;

interface Specification
{
    /**
     * @param ExpressionFactory $factory
     * @return Expression
     */
    public function toExpression(ExpressionFactory $factory): Expression;
}
