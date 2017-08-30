<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class UpdateBookCopyRemarks implements Command
{
    /** @var int|string */
    public $bookCopyID;

    /** @var string */
    public $remarks;

    /**
     * @param int|string $bookCopyID
     * @param string $remarks
     */
    public function __construct($bookCopyID, string $remarks)
    {
        $this->bookCopyID = $bookCopyID;
        $this->remarks = $remarks;
    }
}
