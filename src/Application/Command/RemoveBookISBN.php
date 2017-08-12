<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class RemoveBookISBN implements Command
{
    /** @var int|string */
    public $bookID;

    /**
     * LendBookCopy constructor.
     * @param int|string $bookID
     */
    public function __construct($bookID) {
        $this->bookID = $bookID;
    }
}
