<?php

namespace RJozwiak\Libroteca\Domain\Model\Book;

class Author
{
    const NAME_FORMAT = '/^[ \pL\'\-.]+$/u'; // '/^[ \x{00c0}-\x{01ff}a-zA-Z\'\-.]+$/u'
    const MAX_LENGTH = 100;

    /** @var string */
    private $name;

    /**
     * Author constructor.
     * @param string $name
     * @throws InvalidAuthorException
     */
    public function __construct($name)
    {
        $this->setName(trim($name));
    }

    /**
     * @param string $name
     * @throws InvalidAuthorException
     */
    private function setName($name)
    {
        $this->validate($name);
        $this->name = $name;
    }

    /**
     * @param string $subject
     * @throws InvalidAuthorException
     */
    private function validate($subject)
    {
        $this->assertNotEmpty($subject);
        $this->assertValidFormat($subject);
        $this->assertNotTooLong($subject);
    }

    /**
     * @param string $subject
     * @throws InvalidAuthorException
     */
    private function assertNotEmpty($subject)
    {
        if (empty($subject)) {
            throw InvalidAuthorException::byEmpty();
        }
    }

    /**
     * @param string $subject
     * @throws InvalidAuthorException
     */
    private function assertValidFormat($subject)
    {
        if (!preg_match(self::NAME_FORMAT, $subject)) {
            throw InvalidAuthorException::byFormat();
        }
    }

    /**
     * @param string $subject
     * @throws InvalidAuthorException
     */
    private function assertNotTooLong($subject)
    {
        if (mb_strlen($subject) > self::MAX_LENGTH) {
            throw InvalidAuthorException::byMaxLength();
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
     * @param Author $author
     * @return bool
     */
    public function equals(Author $author)
    {
        return $this->name() === $author->name();
    }
}
