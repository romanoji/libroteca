<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class UpdateBook implements Command
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
    public function __construct(
        $bookID,
        string $isbn,
        array $authors,
        string $title
    ) {
        $this->bookID = $bookID;
        $this->isbn = $isbn;
        $this->authors = $authors;
        $this->title = $title;
    }
}
