<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Specification;

class NotSpecification implements Specification
{
    /** @var Specification */
    private $spec;

    /**
     * @param Specification $spec
     */
    public function __construct(Specification $spec)
    {
        $this->spec = $spec;
    }

    /**
     * @param ExpressionBuilder $builder
     * @return Expression
     */
    public function toExpression(ExpressionBuilder $builder): Expression
    {
        return $builder->not($this->spec);
    }
}
