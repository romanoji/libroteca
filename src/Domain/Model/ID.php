<?php

namespace RJozwiak\Libroteca\Domain\Model;

abstract class ID
{
    /** @var int|string */
    private $id;

    /**
     * ID constructor.
     * @param int|string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return int|string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id;
    }

    /**
     * @param ID $id
     * @return bool
     */
    public function equals(ID $id)
    {
        return
            get_class($this) === get_class($id) &&
            $this->id() === $id->id();
    }
}
