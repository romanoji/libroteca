<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Http\Middleware;

/**
 * Based on https://github.com/laravel/internals/issues/514#issuecomment-299038674
 */
class TransformStringBooleans extends TransformsRequest
{
    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    protected function transform($key, $value)
    {
        if (in_array($value, ['true', 'false'])) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        return $value;
    }
}
