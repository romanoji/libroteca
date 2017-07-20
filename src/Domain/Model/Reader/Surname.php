<?php

namespace RJozwiak\Libroteca\Domain\Model\Reader;

class Surname
{
    private const FORMAT = '/^[\pL\'\-]+$/u';
    private const MAX_LENGTH = 100;

    /** @var string */
    private $surname;

    /**
     * Surname constructor.
     * @param string $surname
     * @throws \InvalidArgumentException
     */
    public function __construct($surname)
    {
        $this->setSurname($surname);
    }

    /**
     * @param string $surname
     * @throws \InvalidArgumentException
     */
    private function setSurname($surname)
    {
        $this->assertNotEmpty($surname);
        $this->assertNotTooLong($surname);
        $this->assertValidFormat($surname);

        $this->surname = $surname;
    }

    /**
     * @param string $surname
     * @throws \InvalidArgumentException
     */
    private function assertNotEmpty($surname)
    {
        if (empty($surname)) {
            throw new \InvalidArgumentException('Empty surname.');
        }
    }

    /**
     * @param string $surname
     * @throws \InvalidArgumentException
     */
    private function assertNotTooLong($surname)
    {
        if (mb_strlen($surname) > self::MAX_LENGTH) {
            throw new \InvalidArgumentException('Too long surname.');
        }
    }

    /**
     * @param string $surname
     * @throws \InvalidArgumentException
     */
    private function assertValidFormat($surname)
    {
        if (!preg_match(self::FORMAT, $surname)) {
            throw new \InvalidArgumentException('Invalid surname format.');
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->surname;
    }

    /**
     * @param Surname $surname
     * @return bool
     */
    public function equals(Surname $surname)
    {
        return $this->surname === $surname->surname;
    }
}
