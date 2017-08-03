<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Book\ISBN;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBNFactory;

class DoctrineISBN extends StringType
{
    /** @var ISBNFactory */
    private $isbnFactory;

    public function __construct()
    {
        $this->isbnFactory = new ISBNFactory();
    }

    public function getName()
    {
        return 'ISBN';
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return ISBN
     * @throws \InvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $this->isbnFactory->create($value);
    }

    /**
     * @param ISBN $value
     * @param AbstractPlatform $platform
     * @return int|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->isbn();
    }
}
