<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Persistence\Doctrine\Type;

use MartinGeorgiev\Doctrine\DBAL\Types\TextArray;

abstract class TypedTextArray extends TextArray
{
    /**
     * @param array $objects
     * @return bool|string
     * @throws \RuntimeException
     */
    protected function transformToPostgresTextArray($objects)
    {
        $class = $this->typeClass();
        $method = $this->valueMethod();
        $this->assertClassExists($class);

        $values = [];
        foreach ($objects as $object) {
            $objectClass = get_class($object);
            if ($objectClass !== $class) {
                throw new \RuntimeException(
                    sprintf("Invalid object type. Expected `{$class}`, but got `{$objectClass}.")
                );
            }

            $values[] = $object->$method();
        }

        return parent::transformToPostgresTextArray($values);
    }

    /**
     * @param string $postgresValue
     * @return array
     * @throws \RuntimeException
     */
    protected function transformFromPostgresTextArray($postgresValue)
    {
        $class = $this->typeClass();
        $this->assertClassExists($class);

        $values = parent::transformFromPostgresTextArray($postgresValue);

        $objects = [];
        foreach ($values as $value) {
            $objects[] = new $class($value);
        }

        return $objects;
    }

    abstract protected function typeClass(): string;

    abstract protected function valueMethod(): string;

    private function assertClassExists(string $className)
    {
        if (!class_exists($className)) {
            throw new \RuntimeException("Class {$className} has not been found.");
        }
    }
}
