<?php

namespace RJozwiak\Libroteca\Domain\Model\Book;

class Author
{
    const NAME_FORMAT = '/^[ \pL\'\-.]+$/u'; // '/^[ \x{00c0}-\x{01ff}a-zA-Z\'\-.]+$/u'
    const MAX_LENGTH = 50;

    /** @var string */
    private $name;

    /** @var string */
    private $surname;

    /**
     * Author constructor.
     * @param string $name
     * @param string $surname
     * @throws InvalidAuthorException
     */
    public function __construct($name, $surname)
    {
        $this->setName(trim($name));
        $this->setSurname(trim($surname));
    }

    /**
     * @param string $name
     * @throws InvalidAuthorException
     */
    private function setName($name)
    {
        $this->validate($name, 'name');
        $this->name = $name;
    }

    /**
     * @param string $surname
     * @throws InvalidAuthorException
     */
    private function setSurname($surname)
    {
        $this->validate($surname, 'surname');
        $this->surname = $surname;
    }

    /**
     * @param string $subject
     * @param string $type
     * @throws InvalidAuthorException
     */
    private function validate($subject, $type = 'name')
    {
        $this->assertNotEmpty($subject, $type);
        $this->assertValidFormat($subject, $type);
        $this->assertNotTooLong($subject, $type);
    }

    /**
     * @param string $subject
     * @param string $type
     * @throws InvalidAuthorException
     */
    private function assertNotEmpty($subject, $type = 'name')
    {
        if (empty($subject)) {
            throw InvalidAuthorException::byEmptyNameOrSurname($type);
        }
    }

    /**
     * @param string $subject
     * @param string $type
     * @throws InvalidAuthorException
     */
    private function assertValidFormat($subject, $type = 'name')
    {
        if (!preg_match(self::NAME_FORMAT, $subject)) {
            throw InvalidAuthorException::byFormat($type);
        }
    }

    /**
     * @param string $subject
     * @param string $type
     * @throws InvalidAuthorException
     */
    private function assertNotTooLong($subject, $type = 'name')
    {
        if (mb_strlen($subject) > self::MAX_LENGTH) {
            throw InvalidAuthorException::byMaxLength($type);
        }
    }

    /**
     * @return string
     */
    public function fullName()
    {
        return $this->name.' '.$this->surname;
    }

    /**
     * @param Author $author
     * @return bool
     */
    public function equals(Author $author)
    {
        return $this->fullName() === $author->fullName();
    }
}
