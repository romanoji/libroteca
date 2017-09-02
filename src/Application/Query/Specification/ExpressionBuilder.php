<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Specification;

interface ExpressionBuilder
{
    /**
     * @param Specification[] $specs
     * @return Expression
     */
    public function and(Specification ...$specs): Expression;

    /**
     * @param Specification[] $specs
     * @return Expression
     */
    public function or(Specification ...$specs): Expression;

    /**
     * @param Specification $spec
     * @return Expression
     */
    public function not(Specification $spec): Expression;
}
