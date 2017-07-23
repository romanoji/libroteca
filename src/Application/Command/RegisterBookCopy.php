<?php
declare(strict_types=1);

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
     * @param $bookCopyID
     * @param $bookID
     */
    public function __construct($bookCopyID, $bookID)
    {
        $this->bookCopyID = $bookCopyID;
        $this->bookID = $bookID;
    }
}
