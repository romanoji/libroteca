<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Specification;

class NotSpecification extends CompositeSpecification
{
    /**
     * @param ExpressionFactory $factory
     * @return Expression
     */
    public function toExpression(ExpressionFactory $factory): Expression
    {
        return $factory->not($this->spec);
    }
}
