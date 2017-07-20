<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model;

abstract class AggregateRoot
{
    /** @var int */
    private $version = 0;

    /**
     * @return ID
     */
    abstract public function id() : ID;

    /**
     * @return int
     */
    public function version() : int
    {
        return $this->version;
    }

    public function incrementVersion() : void
    {
        $this->version++;
    }
}
