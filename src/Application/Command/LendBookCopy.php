<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class LendBookCopy implements Command
{
    // TODO: rename to BorrowBookCopy

    /** @var int|string */
    public $bookLoanID;

    /** @var int|string */
    public $readerID;

    /** @var int|string */
    public $bookCopyID;

    /** @var \DateTimeImmutable */
    public $dueDate;

    /** @var \DateTimeImmutable */
    public $today;

    /**
     * LendBookCopy constructor.
     * @param int|string $bookLoanID
     * @param int|string $readerID
     * @param int|string $bookCopyID
     * @param \DateTimeImmutable $dueDate
     * @param \DateTimeImmutable $today
     */
    public function __construct(
        $bookLoanID,
        $readerID,
        $bookCopyID,
        \DateTimeImmutable $dueDate,
        \DateTimeImmutable $today
    ) {
        $this->bookLoanID = $bookLoanID;
        $this->readerID = $readerID;
        $this->bookCopyID = $bookCopyID;
        $this->dueDate = $dueDate;
        $this->today = $today;
    }
}
