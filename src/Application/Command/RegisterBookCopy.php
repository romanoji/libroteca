<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class RegisterBookCopy implements Command
{
    /** @var string */
    public $bookCopyID;

    /** @var string */
    public $bookID;

    /**
     * RegisterBookCopy constructor.
     * @param string $bookCopyID
     * @param string $bookID
     */
    public function __construct(string $bookCopyID, string $bookID)
    {
        $this->bookCopyID = $bookCopyID;
        $this->bookID = $bookID;
    }
}
