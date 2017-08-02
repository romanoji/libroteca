<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class ReturnBookCopy implements Command
{
    /** @var int|string */
    public $bookLoanID;

    /** @var \DateTimeImmutable */
    public $endDate;

    /** @var null|string */
    public $remarks;

    /**
     * ReturnBookCopy constructor.
     * @param int|string $bookLoanID
     * @param \DateTimeImmutable $endDate
     * @param null|string $remarks
     */
    public function __construct(
        $bookLoanID,
        \DateTimeImmutable $endDate,
        string $remarks = null
    ) {
        $this->bookLoanID = $bookLoanID;
        $this->endDate = $endDate;
        $this->remarks = $remarks;
    }
}
