<?php

namespace RJozwiak\Libroteca\Domain\Model\Reader;

class Name
{
    private const FORMAT = '/^[ \pL]+$/u';
    private const MAX_LENGTH = 50;

    /** @var string */
    private $name;

    /**
     * Name constructor.
     * @param string $name
     * @throws \InvalidArgumentException
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     */
    private function setName($name)
    {
        $this->assertNotEmpty($name);
        $this->assertNotTooLong($name);
        $this->assertValidFormat($name);

        $this->name = $name;
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     */
    private function assertNotEmpty($name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Empty name.');
        }
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     */
    private function assertNotTooLong($name)
    {
        if (mb_strlen($name) > self::MAX_LENGTH) {
            throw new \InvalidArgumentException('Too long name.');
        }
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     */
    private function assertValidFormat($name)
    {
        if (!preg_match(self::FORMAT, $name)) {
            throw new \InvalidArgumentException('Invalid name format.');
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param Name $name
     * @return bool
     */
    public function equals(Name $name)
    {
        return $this->name === $name->name;
    }
}
