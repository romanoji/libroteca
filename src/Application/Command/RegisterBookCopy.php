<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class RegisterBookCopy implements Command
{
    /** @var int|string */
    private $bookCopyID;

    /** @var int|string */
    private $bookID;

    /**
     * @param $bookCopyID
     * @param $bookID
     */
    public function __construct($bookCopyID, $bookID)
    {
        $this->bookCopyID = $bookCopyID;
        $this->bookID = $bookID;
    }

    /**
     * @return int|string
     */
    public function bookCopyID()
    {
        return $this->bookCopyID;
    }

    /**
     * @return int|string
     */
    public function bookID()
    {
        return $this->bookID;
    }
}
