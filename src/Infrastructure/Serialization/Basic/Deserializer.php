<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Serialization\Basic;

interface Deserializer
{
    /**
     * @param array $data
     * @param string $objectClass
     * @return object|mixed
     */
    public function deserialize(array $data, string $objectClass);
}
