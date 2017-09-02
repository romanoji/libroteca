<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Specification;

class AndSpecification implements Specification
{
    /** @var Specification[] */
    private $specs;

    /**
     * @param Specification[] $specs
     */
    public function __construct(Specification ...$specs)
    {
        $this->specs = $specs;
    }

    /**
     * @param ExpressionBuilder $builder
     * @return Expression
     */
    public function toExpression(ExpressionBuilder $builder): Expression
    {
       return $builder->and(...$this->specs);
    }
}
