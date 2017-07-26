<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\BookLoan;

use PhpSpec\ObjectBehavior;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoan;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAlreadyEndedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAlreadyProlongedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\EndingOverdueLoanWithoutRemarksException;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;

class BookLoanSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            new BookLoanID(1),
            new BookCopyID(2),
            new ReaderID(3),
            new \DateTimeImmutable()
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BookLoan::class);
    }

    function it_has_not_ended_by_default()
    {
        $this->hasEnded()->shouldBe(false);
    }

    function it_is_not_prolonged_by_default()
    {
        $this->isProlonged()->shouldBe(false);
    }

    function it_returns_identifier()
    {
        $this->id()->id()->shouldBe(1);
    }

    function it_returns_book_copy_identifier()
    {
        $this->bookCopyID()->id()->shouldBe(2);
    }

    function it_returns_reader_identifier()
    {
        $this->readerID()->id()->shouldBe(3);
    }

    function it_throws_exception_when_due_date_is_earlier_than_today(
        BookLoanID $loanID,
        BookCopyID $bookCopyID,
        ReaderID $readerID
    ) {
        $this->beConstructedWith($loanID, $bookCopyID, $readerID, new \DateTimeImmutable('- 1 day'));

        $this
            ->shouldThrow(
                new \InvalidArgumentException('Due date cannot be earlier than today.')
            )
            ->duringInstantiation();
    }

    function it_throws_exception_when_due_date_exceeds_max_loan_period(
        BookLoanID $loanID,
        BookCopyID $bookCopyID,
        ReaderID $readerID
    ) {
        $this->beConstructedWith($loanID, $bookCopyID, $readerID, new \DateTimeImmutable('+ 61 days'));

        $this
            ->shouldThrow(new \InvalidArgumentException('Exceeded max. loan period.'))
            ->duringInstantiation();
    }

    function it_can_be_ended(
        BookLoanID $loanID,
        BookCopyID $bookCopyID,
        ReaderID $readerID
    ) {
        $today = (new \DateTimeImmutable())->setTime(0, 0, 0);
        $dueDate = $today->modify('+ 30 days');
        $endDate = $today->modify('+ 15 days');

        $this->beConstructedWith($loanID, $bookCopyID, $readerID, $dueDate);

        $this->endLoan($endDate);

        $this->hasEnded()->shouldBe(true);
        $this->endDate()->shouldBeLike($endDate);
    }

    function it_cannot_be_ended_twice(
        BookLoanID $loanID,
        BookCopyID $bookCopyID,
        ReaderID $readerID
    ) {
        $today = (new \DateTimeImmutable())->setTime(0, 0, 0);
        $dueDate = $today->modify('+ 30 days');
        $endDate = $today->modify('+ 15 days');

        $this->beConstructedWith($loanID, $bookCopyID, $readerID, $dueDate);

        $this->endLoan($endDate);
        $this
            ->shouldThrow(new BookLoanAlreadyEndedException('Loan has already ended.'))
            ->during('endLoan', [$endDate]);
    }

    function it_requires_remarks_when_end_date_exceeds_due_date(
        BookLoanID $loanID,
        BookCopyID $bookCopyID,
        ReaderID $readerID
    ) {
        $today = (new \DateTimeImmutable())->setTime(0, 0, 0);
        $dueDate = $today->modify('+ 30 days');
        $endDate = $today->modify('+ 31 days');

        $this->beConstructedWith($loanID, $bookCopyID, $readerID, $dueDate);

        $this
            ->shouldThrow(new EndingOverdueLoanWithoutRemarksException('Ending overdue loan must have remarks.'))
            ->during('endLoan', [$endDate]);
    }

    function it_can_be_prolonged(
        BookLoanID $loanID,
        BookCopyID $bookCopyID,
        ReaderID $readerID
    ) {
        $today = (new \DateTimeImmutable())->setTime(0, 0, 0);
        $dueDate = $today->modify('+ 30 days');
        $prolongedDueDate = $dueDate->modify('+ 30 days');
        $resultDueDate = $today->modify('+ 60 days');

        $this->beConstructedWith($loanID, $bookCopyID, $readerID, $dueDate);

        $this->prolongTo($prolongedDueDate);

        $this->isProlonged()->shouldBe(true);
        $this->dueDate()->shouldBeLike($resultDueDate);
    }

    function it_cannot_be_prolonged_when_it_has_already_ended()
    {
        $today = (new \DateTimeImmutable())->setTime(0, 0, 0);
        $dueDate = $today->modify('+ 30 days');

        $this->endLoan($today);
        $this
            ->shouldThrow(new BookLoanAlreadyEndedException('Loan has already ended.'))
            ->during('prolongTo', [$dueDate]);
    }

    function it_cannot_be_prolonged_twice()
    {
        $dueDate = (new \DateTimeImmutable())
            ->setTime(0, 0, 0)
            ->modify('+ 30 days');

        $this->prolongTo($dueDate);
        $this
            ->shouldThrow(new BookLoanAlreadyProlongedException('Loan is already prolonged.'))
            ->during('prolongTo', [$dueDate]);
    }

    function it_throw_exception_when_prolonged_due_date_is_earlier_than_current_due_date()
    {
        $this
            ->shouldThrow(
                new \InvalidArgumentException('Prolonged due date cannot be earlier than current loan due date.')
            )
            ->during('prolongTo', [new \DateTimeImmutable('- 1 day')]);
    }

    function it_throw_exception_when_prolonged_due_date_exceeds_max_prolongation_period()
    {
        $this
            ->shouldThrow(new \InvalidArgumentException('Exceeded max. prolongation period.'))
            ->during('prolongTo', [new \DateTimeImmutable('+ 31 days')]);
    }
}
