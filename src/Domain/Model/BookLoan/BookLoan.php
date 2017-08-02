<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\BookLoan;

use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAlreadyEndedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAlreadyProlongedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\EndingOverdueLoanWithoutRemarksException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\ProlongOverdueBookLoanException;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;

class BookLoan
{
    public const MAX_ONGOING_LOANS = 5;
    private const MAX_LOAN_PERIOD_IN_DAYS = 60;
    private const MAX_PROLONGATION_PERIOD_IN_DAYS = 30;

    /** @var BookLoanID */
    private $id;

    /** @var BookCopyID */
    private $bookCopyID;

    /** @var ReaderID */
    private $readerID;

    /** @var \DateTimeImmutable */
    private $dueDate;

    /** @var bool */
    private $ended;

    /** @var \DateTimeImmutable */
    private $endDate;

    /** @var bool */
    private $prolonged;

    /** @var string */
    private $remarks;

    // TODO: Good example for event sourcing?

    /**
     * Loan constructor.
     * @param BookLoanID $id
     * @param BookCopyID $bookCopyID
     * @param ReaderID $readerID
     * @param \DateTimeImmutable $dueDate
     * @param \DateTimeImmutable $today
     * @throws \InvalidArgumentException
     * @internal
     */
    public function __construct(
        BookLoanID $id,
        BookCopyID $bookCopyID,
        ReaderID $readerID,
        \DateTimeImmutable $dueDate,
        \DateTimeImmutable $today
    ) {
        $dueDate = $this->clearTime($dueDate);

        $this->assertValidDueDate($dueDate, $today);

        $this->id = $id;
        $this->bookCopyID = $bookCopyID;
        $this->readerID = $readerID;
        $this->dueDate = $dueDate;
        $this->ended = false;
        $this->prolonged = false;
    }

    /**
     * @param \DateTimeImmutable $dueDate
     * @param \DateTimeImmutable $today
     * @throws \InvalidArgumentException
     */
    private function assertValidDueDate(\DateTimeImmutable $dueDate, \DateTimeImmutable $today)
    {
        $today = $this->clearTime($today);
        $maxDueDate = $this->dateAfterDays(self::MAX_LOAN_PERIOD_IN_DAYS);

        if ($dueDate < $today) {
            throw new \InvalidArgumentException('Due date cannot be earlier than today.');
        }

        if ($dueDate > $maxDueDate) {
            throw new \InvalidArgumentException('Exceeded max. loan period.');
        }
    }

    /**
     * @return BookLoanID
     */
    public function id() : BookLoanID
    {
        return $this->id;
    }

    /**
     * @return BookCopyID
     */
    public function bookCopyID() : BookCopyID
    {
        return $this->bookCopyID;
    }

    /**
     * @return ReaderID
     */
    public function readerID() : ReaderID
    {
        return $this->readerID;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function dueDate() : \DateTimeImmutable
    {
        return $this->dueDate;
    }

    /**
     * @return bool
     */
    public function hasEnded() : bool
    {
        return $this->ended;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function endDate() : \DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @return bool
     */
    public function isProlonged() : bool
    {
        return $this->prolonged;
    }

    /**
     * @return string
     */
    public function remarks() : string
    {
        return $this->remarks;
    }

    /**
     * @param \DateTimeImmutable $date
     * @return bool
     */
    public function isOverdue(\DateTimeImmutable $date)
    {
        return $this->dueDate < $date;
    }

    /**
     * @param \DateTimeImmutable $endDate
     * @param string|null $remarks
     * @throws EndingOverdueLoanWithoutRemarksException
     * @throws BookLoanAlreadyEndedException
     */
    public function endLoan(\DateTimeImmutable $endDate, string $remarks = null) : void
    {
        if ($this->hasEnded()) {
            throw new BookLoanAlreadyEndedException('Loan has already ended.');
        }

        $endDate = $this->clearTime($endDate);

        if ($this->isOverdue($endDate) && empty($remarks)) {
            throw new EndingOverdueLoanWithoutRemarksException('Ending overdue loan must have remarks.');
        }

        $this->ended = true;
        $this->endDate = $endDate;
        $this->remarks = $remarks;
    }

    /**
     * @param \DateTimeImmutable $newDueDate
     * @param \DateTimeImmutable $today
     * @throws BookLoanAlreadyEndedException
     * @throws BookLoanAlreadyProlongedException
     * @throws ProlongOverdueBookLoanException
     * @throws \InvalidArgumentException
     */
    public function prolongTo(\DateTimeImmutable $newDueDate, \DateTimeImmutable $today) : void
    {
        if ($this->hasEnded()) {
            throw new BookLoanAlreadyEndedException('Loan has already ended.');
        }
        if ($this->isProlonged()) {
            throw new BookLoanAlreadyProlongedException('Loan is already prolonged.');
        }

        $today = $this->clearTime($today);
        $newDueDate = $this->clearTime($newDueDate);
        $maxProlongedDueDate = $this->dateAfterDays(
            self::MAX_PROLONGATION_PERIOD_IN_DAYS,
            $this->dueDate
        );

        if ($this->isOverdue($today)) {
            throw new ProlongOverdueBookLoanException('Cannot prolong overdue book loan.');
        }
        if ($newDueDate < $this->dueDate) {
            throw new \InvalidArgumentException('Prolongation date cannot be earlier than current loan due date.');
        }
        if ($newDueDate > $maxProlongedDueDate) {
            throw new \InvalidArgumentException('Exceeded max. prolongation period.');
        }

        $this->prolonged = true;
        $this->dueDate = $newDueDate;
    }

    /**
     * @param int $days
     * @param \DateTimeImmutable|null $date
     * @return \DateTimeImmutable
     */
    private function dateAfterDays($days, \DateTimeImmutable $date = null) : \DateTimeImmutable
    {
        if ($date === null) {
            $date = new \DateTimeImmutable();
        }

        return $this->clearTime($date)
            ->modify(sprintf('+ %d days', $days));
    }

    /**
     * @param \DateTimeImmutable $date
     * @return \DateTimeImmutable
     */
    private function clearTime(\DateTimeImmutable $date) : \DateTimeImmutable
    {
        return $date->setTime(0, 0, 0);
    }
}
