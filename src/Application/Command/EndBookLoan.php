<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class EndBookLoan implements Command
{
    /** @var int|string */
    private $bookLoanID;

    /** @var \DateTimeImmutable */
    private $endDate;

    /** @var null|string */
    private $remarks;

    /**
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

    /**
     * @return int|string
     */
    public function bookLoanID()
    {
        return $this->bookLoanID;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function endDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @return null|string
     */
    public function remarks()
    {
        return $this->remarks;
    }
}
