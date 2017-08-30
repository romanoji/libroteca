<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Book;

class Title
{
    /** @var string */
    private $title;

    /**
     * @param string $title
     * @throws \InvalidArgumentException
     */
    public function __construct(string $title)
    {
        $this->setTitle($title);
    }

    /**
     * @param string $title
     * @throws \InvalidArgumentException
     */
    private function setTitle(string $title)
    {
        $this->assertNotEmpty($title);

        $this->title = $title;
    }

    /**
     * @param string $title
     * @throws \InvalidArgumentException
     */
    private function assertNotEmpty(string $title)
    {
        if (empty($title)) {
            throw new \InvalidArgumentException('Empty title.');
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
