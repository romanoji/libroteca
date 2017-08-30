<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Book;

class Author
{
    private const NAME_FORMAT = '/^[ \pL\'\-.]+$/u';

    /** @var string */
    private $name;

    /**
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
    private function assertValidFormat(string $name)
    {
        if (!preg_match(self::NAME_FORMAT, $name)) {
            throw new \InvalidArgumentException('Invalid name format.');
        }
    }

    /**
     * @return string
     */
    public function name() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->name;
    }

    /**
     * @param Author $author
     * @return bool
     */
    public function equals(Author $author) : bool
    {
        return $this->name() === $author->name();
    }
}
