<?php

namespace RJozwiak\Libroteca\Domain\Model\Book;

class Title
{
    const MAX_LENGTH = 100;

    /**
     * @var string
     */
    private $title;

    /**
     * Title constructor.
     * @param string $title
     * @throws InvalidTitleException
     */
    public function __construct($title)
    {
        $this->setTitle(trim($title));
    }

    /**
     * @param string $title
     * @throws InvalidTitleException
     */
    private function setTitle($title)
    {
        $this->validate($title);
        $this->title = $title;
    }

    /**
     * @param string $title
     * @throws InvalidTitleException
     */
    private function validate($title)
    {
        $this->assertNotEmpty($title);
        $this->assertNotTooLong($title);
        $this->title = $title;
    }

    /**
     * @param string $title
     * @throws InvalidTitleException
     */
    private function assertNotEmpty($title)
    {
        if (empty($title)) {
            throw InvalidTitleException::byEmpty();
        }
    }

    /**
     * @param string $title
     * @throws InvalidTitleException
     */
    private function assertNotTooLong($title)
    {
        if (mb_strlen($title) > self::MAX_LENGTH) {
            throw InvalidTitleException::byMaxLength();
        }
    }

    /**
     * @return string
     */
    public function title()
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
