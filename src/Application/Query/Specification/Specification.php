<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Specification;

interface Specification
{
    /**
     * @param ExpressionBuilder $builder
     * @return Expression
     */
    public function toExpression(ExpressionBuilder $builder): Expression;
}
