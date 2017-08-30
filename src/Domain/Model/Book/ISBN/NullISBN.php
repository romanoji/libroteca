<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Book\ISBN;

class NullISBN extends ISBN
{
    public function __construct()
    {
        parent::__construct(null);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '';
    }

    protected function assertValidFormat(?string $isbn)
    {
    }

    /**
     * Regex ISBN format
     * @return string
     */
    protected function format(): string
    {
    }

    protected function assertValidChecksum(?string $isbn)
    {
    }

    /**
     * @param string $isbn
     * @return string
     */
    protected function checksumDigit(?string $isbn): string
    {
    }
}
