<?php

namespace RJozwiak\Libroteca\Domain\Model\Book;

class Title
{
    /** @var string */
    private $title;

    public function __construct($title)
    {
        $this->setTitle($title);
    }

    private function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @param Title $title
     * @return bool
     */
    public function equals(Title $title)
    {
        return $this->title() === $title->title();
    }
}
