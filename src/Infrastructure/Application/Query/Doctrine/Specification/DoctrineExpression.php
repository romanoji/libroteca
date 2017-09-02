<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine\Specification;

use Doctrine\ORM\Query\Expr;
use RJozwiak\Libroteca\Application\Query\Specification\Expression;

class DoctrineExpression implements Expression
{
    /** @var Expr|Expr\* */
    private $expr;

    /**
     * @param Expr|Expr\* $expr
     */
    public function __construct($expr)
    {
        $this->expr = $expr;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->expr->__toString();
    }
}
