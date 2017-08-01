<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class ProlongBookLoan implements Command
{
    /** @var int|string */
    public $bookLoanID;

    /** @var string */
    public $newDueDate;

    /** @var \DateTimeImmutable */
    public $today;

    /**
     * ProlongLoan constructor.
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
}
