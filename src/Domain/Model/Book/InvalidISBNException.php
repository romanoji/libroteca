<?php

namespace RJozwiak\Libroteca\Domain\Model\Book;

class InvalidISBNException extends \InvalidArgumentException
{
    /**
     * @return self
     */
    public static function byFormat()
    {
        return new self('ISBN has not valid format.');
    }

    /**
     * @return self
     */
    public static function byChecksum()
    {
        return new self('ISBN checksum verification failed.');
    }
}
