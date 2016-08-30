<?php

namespace RJozwiak\Libroteca\Domain\Model\Book;

class Author
{
    /** @var string */
    private $name;
    
    public function __construct($name)
    {
        $this->setName($name);
    }

    private function setName($name)
    {
        $this->name = $name;
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
