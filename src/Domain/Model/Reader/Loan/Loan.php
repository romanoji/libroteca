<?php

namespace RJozwiak\Libroteca\Domain\Model\Reader\Loan;

use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\Reader\Loan\Exception\EndingOverdueLoanWithoutRemarksException;
use RJozwiak\Libroteca\Domain\Model\Reader\Loan\Exception\LoanAlreadyEndedException;
use RJozwiak\Libroteca\Domain\Model\Reader\Loan\Exception\LoanAlreadyProlongedException;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;

class Loan
{
    private const MAX_PERIOD_IN_DAYS = 60;
    private const MAX_PROLONGATION_PERIOD_IN_DAYS = 30;

    /** @var LoanID */
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
     * @param LoanID $id
     * @param BookCopyID $bookCopyID
     * @param ReaderID $readerID
     * @param \DateTimeImmutable $dueDate
     * @throws \InvalidArgumentException
     */
    public function __construct(
        LoanID $id,
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
     * @return LoanID
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return BookCopyID
     */
    public function bookCopyID()
    {
        return $this->bookCopyID;
    }

    /**
     * @return ReaderID
     */
    public function readerID()
    {
        return $this->readerID;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function dueDate()
    {
        return $this->dueDate;
    }

    /**
     * @return bool
     */
    public function hasEnded()
    {
        return $this->ended;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function endDate()
    {
        return $this->endDate;
    }

    /**
     * @return bool
     */
    public function isProlonged()
    {
        return $this->prolonged;
    }

    /**
     * @return string
     */
    public function remarks()
    {
        return $this->remarks;
    }

    /**
     * @param \DateTimeImmutable $endDate
     * @param string|null $remarks
     * @throws EndingOverdueLoanWithoutRemarksException
     * @throws LoanAlreadyEndedException
     */
    public function endLoan(\DateTimeImmutable $endDate, string $remarks = null)
    {
        if ($this->hasEnded()) {
            throw new LoanAlreadyEndedException('Loan has already ended.');
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
     * @throws LoanAlreadyEndedException
     * @throws LoanAlreadyProlongedException
     * @throws \InvalidArgumentException
     */
    public function prolongTo(\DateTimeImmutable $newDueDate)
    {
        if ($this->hasEnded()) {
            throw new LoanAlreadyEndedException('Loan has already ended.');
        }
        if ($this->isProlonged()) {
            throw new LoanAlreadyProlongedException('Loan is already prolonged.');
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
    private function dateAfterDays($days, \DateTimeImmutable $date = null)
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
    private function clearTime(\DateTimeImmutable $date)
    {
        return $date->setTime(0, 0, 0);
    }
}
