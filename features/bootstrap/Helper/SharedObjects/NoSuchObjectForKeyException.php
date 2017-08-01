<?php
declare(strict_types=1);

namespace Helper\SharedObjects;

class NoSuchObjectForKeyException extends \RuntimeException
{
    /**
     * @param string $key
     * @return self
     */
    public static function fromKey(string $key) : self
    {
        return new self("No such object for key `$key`.");
    }
}
