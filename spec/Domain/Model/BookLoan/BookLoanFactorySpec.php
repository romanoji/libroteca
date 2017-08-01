<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\BookLoan;

use PhpSpec\ObjectBehavior;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoan;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanFactory;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanRepository;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookCopyAlreadyBorrowedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAttemptWhenHavingOverdueLoanException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\MaxOngoingLoansExceededException;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;

class BookLoanFactorySpec extends ObjectBehavior
{
    function let(BookLoanRepository $bookLoanRepository)
    {
        $this->beConstructedWith($bookLoanRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BookLoanFactory::class);
    }

    function it_creates_book_loan(BookLoanRepository $bookLoanRepository)
    {
        $bookLoanID = new BookLoanID(1);
        $bookCopyID = new BookCopyID(2);
        $readerID = new ReaderID(3);
        $date = (new \DateTimeImmutable())->setTime(0, 0, 0);

        $bookLoanRepository->findOngoingByBookCopyID($bookCopyID)->willReturn(null);
        $bookLoanRepository->findOngoingByReaderID($readerID)->willReturn([]);

        $this
            ->create($bookLoanID, $bookCopyID, $readerID, $date, $date)
            ->shouldReturnAnInstanceOf(BookLoan::class);
    }

    function it_throws_exception_on_loan_creation_if_book_is_already_borrowed(
        BookLoanRepository $bookLoanRepository,
        BookLoan $bookLoan
    ) {
        $bookLoanID = new BookLoanID(1);
        $bookCopyID = new BookCopyID(2);
        $readerID = new ReaderID(3);
        $date = (new \DateTimeImmutable())->setTime(0, 0, 0);

        $bookLoanRepository->findOngoingByBookCopyID($bookCopyID)->willReturn($bookLoan);
        $bookLoanRepository->findOngoingByReaderID($readerID)->willReturn([]);

        $this
            ->shouldThrow(BookCopyAlreadyBorrowedException::class)
            ->during('create', [$bookLoanID, $bookCopyID, $readerID, $date, $date]);
    }

    function it_throws_exception_on_loan_creation_if_exceeded_max_ongoing_loans_for_a_reader(
        BookLoanRepository $bookLoanRepository,
        BookLoan $bookLoan
    ) {
        $bookLoanID = new BookLoanID(1);
        $bookCopyID = new BookCopyID(2);
        $readerID = new ReaderID(3);
        $date = (new \DateTimeImmutable())->setTime(0, 0, 0);

        $bookLoanRepository->findOngoingByBookCopyID($bookCopyID)->willReturn(null);
        $bookLoanRepository
            ->findOngoingByReaderID($readerID)
            ->willReturn(array_fill(0, 5, $bookLoan));

        $this
            ->shouldThrow(MaxOngoingLoansExceededException::class)
            ->during('create', [$bookLoanID, $bookCopyID, $readerID, $date, $date]);
    }

    function it_throws_exception_on_reader_attempt_to_borrow_books_when_having_overdue_loan(
        BookLoanRepository $bookLoanRepository,
        BookLoan $bookLoan
    ) {
        $bookLoanID = new BookLoanID(1);
        $bookCopyID = new BookCopyID(2);
        $readerID = new ReaderID(3);
        $date = (new \DateTimeImmutable())->setTime(0, 0, 0);

        $bookLoanRepository->findOngoingByBookCopyID($bookCopyID)->willReturn(null);
        $bookLoanRepository->findOngoingByReaderID($readerID)->willReturn([$bookLoan]);
        $bookLoan->isOverdue($date)->willReturn(true);

        $this
            ->shouldThrow(BookLoanAttemptWhenHavingOverdueLoanException::class)
            ->during('create', [$bookLoanID, $bookCopyID, $readerID, $date, $date]);
    }
}
