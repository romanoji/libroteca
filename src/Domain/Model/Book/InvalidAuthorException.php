<?php

namespace RJozwiak\Libroteca\Domain\Model\Book;

class InvalidAuthorException extends \InvalidArgumentException
{
    /**
     * @param string $type
     * @return self
     */
    public static function byEmptyNameOrSurname($type = 'name')
    {
        return new self("Author's {$type} must not be empty");
    }

    /**
     * @param string $type
     * @return self
     */
    public static function byFormat($type = 'name')
    {
        return new self("Author's {$type} has invalid format");
    }

    /**
     * @param string $type
     * @return self
     */
    public static function byMaxLength($type = 'name')
    {
        return new self(
            sprintf(
                "Author's %s exceeds maximum length of %s characters",
                $type,
                Author::MAX_LENGTH
            )
        );
    }
}
