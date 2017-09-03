<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine\Specification\ORM;

use Doctrine\ORM\Query\Expr;
use RJozwiak\Libroteca\Application\Query\Specification\{
    Expression, SimpleExpression, Specification
};
use RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine\Specification\ExpressionFactory;

class ORMExpressionFactory extends ExpressionFactory
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

        return new SimpleExpression($expr->__toString());
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

        return new SimpleExpression($expr->__toString());
    }

    /**
     * @param Specification[] $specs
     * @return string[]
     */
    private function transformSpecsToExpressions(array $specs): array
    {
        return array_map(
            function (Specification $spec) {
                return $spec->toExpression($this)->value();
            },
            $specs
        );
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

        return new SimpleExpression($expr->__toString());
    }

    /**
     * @param mixed $field
     * @param mixed $value
     * @return Expression
     * @throws \InvalidArgumentException
     */
    public function equals($field, $value): Expression
    {
        $valueType = $this->resolveValueType($value);
        $expr = (new Expr())->eq(
            $field,
            $this->expressionBuilder->literal($value, $valueType)
        );

        return new SimpleExpression($expr->__toString());
    }

    /**
     * @param mixed $field
     * @param string $value
     * @return Expression
     * @throws \InvalidArgumentException
     */
    public function like($field, string $value): Expression
    {
        $expr = (new Expr())->like(
            (new Expr())->lower($field),
            (new Expr())->lower($this->expressionBuilder->literal('%'.$value.'%', 'string'))
        );

        return new SimpleExpression($expr->__toString());
    }

    /**
     * @param mixed $field
     * @return Expression
     */
    public function isNull($field): Expression
    {
        $expr = (new Expr())->isNull($field);

        return new SimpleExpression($expr);
    }
}
