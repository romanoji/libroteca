<?php

namespace RJozwiak\Libroteca\Domain\Model\Book;

class InvalidAuthorException extends \InvalidArgumentException
{
    /**
     * @return self
     */
    public static function byEmpty()
    {
        return new self("Author's name must not be empty");
    }

    /**
     * @return self
     */
    public static function byFormat()
    {
        return new self("Author's name has invalid format");
    }

    /**
     * @return self
     */
    public static function byMaxLength()
    {
        return new self(
            sprintf(
                "Author's name exceeds maximum length of %s characters",
                Author::MAX_LENGTH
            )
        );
    }
}
