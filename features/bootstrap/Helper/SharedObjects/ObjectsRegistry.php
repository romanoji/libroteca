<?php
declare(strict_types=1);

namespace Helper\SharedObjects;

class ObjectsRegistry
{
    /** @var array */
    private $sharedObjects = [];

    /**
     * @param string $key
     * @return object
     * @throws NoSuchObjectForKeyException
     */
    public function load(string $key)
    {
        if (!isset($this->sharedObjects[$key])) {
            throw NoSuchObjectForKeyException::fromKey($key);
        }

        return $this->sharedObjects[$key];
    }

    /**
     * @param string $key
     * @param object $object
     */
    public function save(string $key, $object) : void
    {
        $this->sharedObjects[$key] = $object;
    }
}
