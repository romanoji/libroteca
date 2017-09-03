<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Specification;

class LikeSpecification implements Specification
{
    /** @var string */
    protected $field;

    /** @var mixed */
    protected $value;

    /**
     * @param string $field
     * @param mixed $value
     */
    public function __construct(string $field, $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    /**
     * @param ExpressionFactory $factory
     * @return Expression
     */
    public function toExpression(ExpressionFactory $factory): Expression
    {
        return $factory->like($this->field, $this->value);
    }
}
