<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine\Specification;

use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use RJozwiak\Libroteca\Application\Query\Specification\ExpressionFactory as ExpressionFactoryInterface;

abstract class ExpressionFactory implements ExpressionFactoryInterface
{
    protected const ALLOWED_TYPES = ['boolean', 'integer', 'string', 'double'];

    /** @var ExpressionBuilder */
    protected $expressionBuilder;

    /**
     * @param ExpressionBuilder $expressionBuilder
     */
    public function __construct(ExpressionBuilder $expressionBuilder)
    {
        $this->expressionBuilder = $expressionBuilder;
    }

    /**
     * @param mixed $value
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function resolveValueType($value): string
    {
        $valueType = gettype($value);

        if (!in_array($valueType, self::ALLOWED_TYPES)) {
            throw new \InvalidArgumentException('Invalid value type for "equals" expression.');
        }

        if ($valueType === 'double') {
            $valueType = 'float';
        }

        return $valueType;
    }
}
