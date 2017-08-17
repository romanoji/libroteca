<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Serialization\Basic;

class ObjectDeserializer implements Deserializer
{
    /**
     * @param array $data
     * @param string $objectClass
     * @return object|mixed
     * @throws \ReflectionException
     */
    public function deserialize(array $data, string $objectClass)
    {
        $reflection = new \ReflectionClass($objectClass);
        $object = $reflection->newInstanceWithoutConstructor();

        $this->hydrate($data, $object, $objectClass);

        return $object;
    }

    /**
     * @param array $data
     * @param $object
     * @param string $objectClass
     * @throws \ReflectionException
     */
    private function hydrate(array $data, $object, string $objectClass)
    {
        foreach ($data as $property => $value) {
            $reflectionProperty = new \ReflectionProperty($objectClass, $property);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($object, $value);
        }
    }
}
