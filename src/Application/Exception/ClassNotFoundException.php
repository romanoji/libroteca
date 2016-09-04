<?php

namespace RJozwiak\Libroteca\Application\Exception;

class ClassNotFoundException extends \RuntimeException
{
    /**
     * @param string $className
     * @return ClassNotFoundException
     */
    public static function fromClass($className)
    {
        return new self("Class $className not found.");
    }
}
