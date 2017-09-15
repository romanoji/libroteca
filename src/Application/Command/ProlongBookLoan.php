<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class ProlongBookLoan implements Command
{
    /** @var int|string */
    private $bookLoanID;

    /** @var \DateTimeImmutable */
    private $newDueDate;

    /** @var \DateTimeImmutable */
    private $today;

    /**
     * @param int|string $bookLoanID
     * @param \DateTimeImmutable $newDueDate
     * @param \DateTimeImmutable $today
     */
    public function __construct(
        $bookLoanID,
        \DateTimeImmutable $newDueDate,
        \DateTimeImmutable $today
    ) {
        $this->bookLoanID = $bookLoanID;
        $this->newDueDate = $newDueDate;
        $this->today = $today;
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
    public function newDueDate(): \DateTimeImmutable
    {
        return $this->newDueDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function today(): \DateTimeImmutable
    {
        return $this->today;
    }
}
