<?php

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class RegisterBookCopy implements Command
{
    /** @var int|string */
    public $bookCopyID;

    /** @var int|string */
    public $bookID;

    /**
     * RegisterBookCopy constructor.
     * @param int|string $bookCopyID
     * @param int|string $bookID
     */
    public function __construct($bookCopyID, $bookID)
    {
        $this->bookCopyID = $bookCopyID;
        $this->bookID = $bookID;
    }
}
