<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader\BookLoan;

use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\Reader\BookLoan\Exception\EndingOverdueLoanWithoutRemarksException;
use RJozwiak\Libroteca\Domain\Model\Reader\BookLoan\Exception\BookLoanAlreadyEndedException;
use RJozwiak\Libroteca\Domain\Model\Reader\BookLoan\Exception\BookLoanAlreadyProlongedException;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;

class BookLoan
{
    private const MAX_PERIOD_IN_DAYS = 60;
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

    // TODO: Event sourcing here?

    /**
     * Loan constructor.
     * @param BookLoanID $id
     * @param BookCopyID $bookCopyID
     * @param ReaderID $readerID
     * @param \DateTimeImmutable $dueDate
     * @throws \InvalidArgumentException
     */
    public function __construct(
        BookLoanID $id,
        BookCopyID $bookCopyID,
        ReaderID $readerID,
        \DateTimeImmutable $dueDate
    ) {
        $dueDate = $this->clearTime($dueDate);

        $this->assertValidDueDate($dueDate);

        $this->id = $id;
        $this->bookCopyID = $bookCopyID;
        $this->readerID = $readerID;
        $this->dueDate = $dueDate;
        $this->ended = false;
        $this->prolonged = false;
    }

    /**
     * @param \DateTimeImmutable $dueDate
     * @throws \InvalidArgumentException
     */
    private function assertValidDueDate(\DateTimeImmutable $dueDate)
    {
        $today = $this->clearTime(new \DateTimeImmutable());
        $maxDueDate = $this->dateAfterDays(self::MAX_PERIOD_IN_DAYS);

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

        if ($endDate > $this->dueDate && empty($remarks)) {
            throw new EndingOverdueLoanWithoutRemarksException('Ending overdue loan must have remarks.');
        }

        $this->ended = true;
        $this->endDate = $endDate;
        $this->remarks = $remarks;
    }

    /**
     * @param \DateTimeImmutable $newDueDate
     * @throws BookLoanAlreadyEndedException
     * @throws BookLoanAlreadyProlongedException
     * @throws \InvalidArgumentException
     */
    public function prolongTo(\DateTimeImmutable $newDueDate) : void
    {
        if ($this->hasEnded()) {
            throw new BookLoanAlreadyEndedException('Loan has already ended.');
        }
        if ($this->isProlonged()) {
            throw new BookLoanAlreadyProlongedException('Loan is already prolonged.');
        }

        $newDueDate = $this->clearTime($newDueDate);
        $maxProlongedDueDate = $this->dateAfterDays(
            self::MAX_PROLONGATION_PERIOD_IN_DAYS,
            $this->dueDate
        );

        if ($newDueDate < $this->dueDate) {
            throw new \InvalidArgumentException('Prolonged due date cannot be earlier than current loan due date.');
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
