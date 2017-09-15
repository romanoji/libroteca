<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class UpdateBookCopyRemarks implements Command
{
    /** @var int|string */
    private $bookCopyID;

    /** @var string */
    private $remarks;

    /**
     * @param int|string $bookCopyID
     * @param string $remarks
     */
    public function __construct($bookCopyID, string $remarks)
    {
        $this->bookCopyID = $bookCopyID;
        $this->remarks = $remarks;
    }

    /**
     * @return int|string
     */
    public function bookCopyID()
    {
        return $this->bookCopyID;
    }

    /**
     * @return string
     */
    public function remarks(): string
    {
        return $this->remarks;
    }
}
