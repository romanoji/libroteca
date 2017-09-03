<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Specification;

class AndSpecification extends CompositeSpecifications
{
    /**
     * @param ExpressionFactory $factory
     * @return Expression
     */
    public function toExpression(ExpressionFactory $factory): Expression
    {
        return $factory->and(...$this->specs);
    }
}
