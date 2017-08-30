<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Domain\Model\ID;

abstract class DoctrineEntityID extends GuidType
{
    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return int|string
     * @throws \InvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return Uuid::fromString($value);
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return ID
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $className = $this->getNamespace();

        return new $className($value);
    }

    /**
     * @return string
     */
    abstract protected function getNamespace(): string;
}
