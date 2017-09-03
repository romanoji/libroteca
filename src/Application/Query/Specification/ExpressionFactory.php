<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Specification;

interface ExpressionFactory
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

    /**
     * @param mixed $field
     * @param mixed $value
     * @return Expression
     */
    public function equals($field, $value): Expression;

    /**
     * @param mixed $field
     * @param string $value
     * @return Expression
     */
    public function like($field, string $value): Expression;

    /**
     * @param mixed $field
     * @return Expression
     */
    public function isNull($field): Expression;

    // TODO: and so on...
}
