<?php

namespace RJozwiak\Libroteca\Domain\Model\Book;

class Author
{
    const NAME_FORMAT = '/^[ \pL\'\-.]+$/u';

    /** @var string */
    private $name;

    /**
     * Author constructor.
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
    private function assertValidFormat($name)
    {
        if (!preg_match(self::NAME_FORMAT, $name)) {
            throw new \InvalidArgumentException('Invalid name format.');
        }
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param Author $author
     * @return bool
     */
    public function equals(Author $author)
    {
        return $this->name() === $author->name();
    }
}
