<?php
declare(strict_types=1);

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
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     */
    private function setName(string $name)
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
    private function assertNotEmpty(string $name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Empty name.');
        }
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     */
    private function assertNotTooLong(string $name)
    {
        if (mb_strlen($name) > self::MAX_LENGTH) {
            throw new \InvalidArgumentException('Too long name.');
        }
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     */
    private function assertValidFormat(string $name)
    {
        if (!preg_match(self::FORMAT, $name)) {
            throw new \InvalidArgumentException('Invalid name format.');
        }
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->name;
    }

    /**
     * @param Name $name
     * @return bool
     */
    public function equals(Name $name) : bool
    {
        return $this->name === $name->name;
    }
}
