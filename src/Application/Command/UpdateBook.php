<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class UpdateBook implements Command
{
    /** @var int|string */
    private $bookID;

    /** @var null|string */
    private $isbn;

    /** @var array */
    private $authors;

    /** @var string */
    private $title;

    /**
     * @param int|string $bookID
     * @param null|string $isbn
     * @param array $authors
     * @param string $title
     */
    public function __construct(
        $bookID,
        ?string $isbn,
        array $authors,
        string $title
    ) {
        $this->bookID = $bookID;
        $this->isbn = $isbn;
        $this->authors = $authors;
        $this->title = $title;
    }

    /**
     * @return int|string
     */
    public function bookID()
    {
        return $this->bookID;
    }

    /**
     * @return null|string
     */
    public function isbn()
    {
        return $this->isbn;
    }

    /**
     * @return array
     */
    public function authors(): array
    {
        return $this->authors;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }
}
