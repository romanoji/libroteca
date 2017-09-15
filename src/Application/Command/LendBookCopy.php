<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class LendBookCopy implements Command
{
    // TODO: rename to BorrowBookCopy ?

    /** @var int|string */
    private $bookLoanID;

    /** @var int|string */
    private $readerID;

    /** @var int|string */
    private $bookCopyID;

    /** @var \DateTimeImmutable */
    private $dueDate;

    /** @var \DateTimeImmutable */
    private $today;

    /**
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

    /**
     * @return int|string
     */
    public function bookLoanID()
    {
        return $this->bookLoanID;
    }

    /**
     * @return int|string
     */
    public function readerID()
    {
        return $this->readerID;
    }

    /**
     * @return int|string
     */
    public function bookCopyID()
    {
        return $this->bookCopyID;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function dueDate(): \DateTimeImmutable
    {
        return $this->dueDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function today(): \DateTimeImmutable
    {
        return $this->today;
    }
}
