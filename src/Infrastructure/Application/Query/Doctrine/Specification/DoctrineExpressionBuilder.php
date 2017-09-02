<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine\Specification;

use Doctrine\ORM\Query\Expr;
use RJozwiak\Libroteca\Application\Query\Specification\Expression;
use RJozwiak\Libroteca\Application\Query\Specification\ExpressionBuilder;
use RJozwiak\Libroteca\Application\Query\Specification\Specification;

class DoctrineExpressionBuilder implements ExpressionBuilder
{
    /**
     * @param Specification[] $specs
     * @return Expression
     */
    public function and(Specification ...$specs): Expression
    {
        $expr = (new Expr())->andX(
            ...$this->transformSpecsToExpressions($specs)
        );

        return new DoctrineExpression($expr);
    }

    /**
     * @param Specification[] $specs
     * @return Expression
     */
    public function or(Specification ...$specs): Expression
    {
        $expr = (new Expr())->orX(
            ...$this->transformSpecsToExpressions($specs)
        );

        return new DoctrineExpression($expr);
    }

    /**
     * @param Specification $spec
     * @return Expression
     */
    public function not(Specification $spec): Expression
    {
        $expr = (new Expr())->not(
            $spec->toExpression($this)->value()
        );

        return new DoctrineExpression($expr);
    }

    /**
     * @param Specification[] $specs
     * @return string[]
     */
    private function transformSpecsToExpressions(array $specs) : array
    {
        return array_map(
            function (Specification $spec) {
                return $spec->toExpression($this)->value();
            },
            $specs
        );
    }
}
