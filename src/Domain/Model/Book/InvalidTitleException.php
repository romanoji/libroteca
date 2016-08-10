<?php

namespace RJozwiak\Libroteca\Domain\Model\Book;

class InvalidTitleException extends \InvalidArgumentException
{
    public static function byEmpty()
    {
        return new self('Title must not be empty');
    }

    public static function byMaxLength()
    {
        return new self(
            sprintf('Title length exceeds maximum of %s characters', Title::MAX_LENGTH)
        );
    }
}
