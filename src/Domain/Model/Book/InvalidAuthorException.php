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
     * @param string $nameOrSurname
     * @param string $type
     * @return self
     */
    public static function byFormat($nameOrSurname, $type = 'name')
    {
        return new self("Author's {$type} '{$nameOrSurname}' has invalid format");
    }

    /**
     * @param string $nameOrSurname
     * @param string $type
     * @return self
     */
    public static function byMaxLength($nameOrSurname, $type = 'name')
    {
        return new self(
            sprintf(
                "Author's %s '%s' exceeds maximum length of %s characters",
                $type,
                $nameOrSurname,
                Author::MAX_LENGTH
            )
        );
    }
}
