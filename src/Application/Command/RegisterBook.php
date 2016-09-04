<?php

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class RegisterBook implements Command
{
    /** @var int|string */
    public $bookID;

    /** @var string */
    public $isbn;

    /** @var array */
    public $authors;

    /** @var string */
    public $title;

    /**
     * RegisterBook constructor.
     * @param int|string $bookID
     * @param string $isbn
     * @param array $authors
     * @param string $title
     */
    public function __construct($bookID, $isbn, array $authors, $title)
    {
        $this->bookID = $bookID;
        $this->isbn = $isbn;
        $this->authors = $authors;
        $this->title = $title;
    }
}
