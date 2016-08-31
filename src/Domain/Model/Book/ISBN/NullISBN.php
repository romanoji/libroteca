<?php

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
    public function __toString()
    {
        return '';
    }

    /**
     * @param string $isbn
     */
    protected function assertValidFormat($isbn)
    {
    }

    /**
     * Regex ISBN format
     * @return string
     */
    protected function format()
    {
    }

    /**
     * @param string $isbn
     */
    protected function assertValidChecksum($isbn)
    {
    }

    /**
     * @param string $isbn
     * @return string
     */
    protected function checksumDigit($isbn)
    {
    }
}
