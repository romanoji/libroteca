<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen\Specification;

use RJozwiak\Libroteca\Application\Query\Specification\Expression;
use RJozwiak\Libroteca\Application\Query\Specification\ExpressionFactory as ExpressionFactoryInterface;
use RJozwiak\Libroteca\Application\Query\Specification\Specification;

class ExpressionFactory implements ExpressionFactoryInterface
{
    /**
     * @param Specification[] $specs
     * @return Expression
     */
    public function and(Specification ...$specs): Expression
    {
        $exprs = $this->transformSpecsToExpressions($specs);

        return new QueryBuilderExpression(
            function ($query) use ($exprs) {
                foreach ($exprs as $expr) {
                    $query->where($expr);
                }
            }
        );
    }

    /**
     * @param Specification[] $specs
     * @return Expression
     */
    public function or(Specification ...$specs): Expression
    {
        $exprs = $this->transformSpecsToExpressions($specs);

        return new QueryBuilderExpression(
            function ($query) use ($exprs) {
                foreach ($exprs as $expr) {
                    $query->orWhere($expr);
                }
            }
        );
    }

    /**
     * @param Specification[] $specs
     * @return \Closure[]
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
        /** @var \Closure $expr */
        $expr = $spec->toExpression($this)->value();

        return new QueryBuilderExpression(
            function ($query) use ($expr) {
                $query->where(
                    function () use ($expr, $query) { $expr($query); },
                    null,
                    null,
                    'not'
                );
            }
        );
    }

    /**
     * @param mixed $field
     * @param mixed $value
     * @return Expression
     */
    public function equals($field, $value): Expression
    {
        return new QueryBuilderExpression(
            function ($query) use ($field, $value) {
                $query->where($field, '=', $value);
            }
        );
    }

    /**
     * @param mixed $field
     * @param string $value
     * @return Expression
     */
    public function like($field, string $value): Expression
    {
        return new QueryBuilderExpression(
            function ($query) use ($field, $value) {
                $query->where($field, 'LIKE', '%'.$value.'%');
            }
        );
    }

    /**
     * @param mixed $field
     * @return Expression
     */
    public function isNull($field): Expression
    {
        return new QueryBuilderExpression(
            function ($query) use ($field) {
                $query->whereNull($field);
            }
        );
    }
}
