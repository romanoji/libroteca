<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopy;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\Reader\BookLoan\BookLoan;
use RJozwiak\Libroteca\Domain\Model\Reader\BookLoan\BookLoanFactory;
use RJozwiak\Libroteca\Domain\Model\Reader\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\Reader\Email;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\MaxNumberOfBookLoansExceededException;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderCannotReturnNotBorrowedBook;
use RJozwiak\Libroteca\Domain\Model\Reader\Name;
use RJozwiak\Libroteca\Domain\Model\Reader\Phone;
use RJozwiak\Libroteca\Domain\Model\Reader\Reader;
use PhpSpec\ObjectBehavior;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Domain\Model\Reader\Surname;

class ReaderSpec extends ObjectBehavior
{
    function let(ReaderID $readerID)
    {
        $readerID->id()->willReturn(1337);

        $this->beConstructedWith(
            $readerID,
            new Name('John'),
            new Surname('Kowalsky'),
            new Email('john.kowalsky@mail.com'),
            new Phone('+48 123456789')
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Reader::class);
    }

    function it_returns_identifier()
    {
        $this->id()->id()->shouldReturn(1337);
    }

    function it_returns_fullname()
    {
        $this->fullname()->shouldReturn('John Kowalsky');
    }

    function it_returns_email()
    {
        $this->email()->shouldBeLike(new Email('john.kowalsky@mail.com'));
    }

    function it_returns_phone()
    {
        $this->phone()->shouldBeLike(new Phone('+48 123456789'));
    }

    function it_returns_null_if_loan_was_not_found(BookLoanID $bookLoanID)
    {
        $this->bookLoan($bookLoanID)->shouldBeNull();
    }

    function it_can_borrow_a_book_copy_for_some_period_of_time(
        ReaderID $readerID,
        BookLoanFactory $bookLoanFactory,
        BookCopy $someBookCopy,
        BookLoan $someBookLoan,
        BookCopy $otherBookCopy,
        BookLoan $otherBookLoan,
        \DateTimeImmutable $dueDate
    ) {
        $someBookCopyID = new BookCopyID(1);
        $someBookLoanID = new BookLoanID(1);
        $otherBookCopyID = new BookCopyID(2);
        $otherBookLoanID = new BookLoanID(2);

        $someBookCopy->id()->willReturn($someBookCopyID);
        $someBookLoan->id()->willReturn($someBookLoanID);
        $otherBookCopy->id()->willReturn($otherBookCopyID);
        $otherBookLoan->id()->willReturn($otherBookLoanID);
        $bookLoanFactory->create($someBookCopyID, $readerID, $dueDate)->willReturn($someBookLoan);
        $bookLoanFactory->create($otherBookCopyID, $readerID, $dueDate)->willReturn($otherBookLoan);

        $this->borrowBookCopy($bookLoanFactory, $someBookCopy, $dueDate);
        $this->borrowBookCopy($bookLoanFactory, $otherBookCopy, $dueDate);

        $this->bookLoan($someBookLoanID)->shouldReturn($someBookLoan);
        $this->bookLoan($otherBookLoanID)->shouldReturn($otherBookLoan);
    }

    function it_throws_exception_when_borrowing_another_book_exceeds_max_loans_limit(
        ReaderID $readerID,
        BookLoanFactory $bookLoanFactory,
        BookCopyID $bookCopyID,
        BookCopy $bookCopy,
        BookLoan $bookLoan,
        \DateTimeImmutable $dueDate
    ) {
        $bookCopy->id()->willReturn($bookCopyID);
        $bookLoanFactory->create($bookCopyID, $readerID, $dueDate)->willReturn($bookLoan);

        for ($i = 0; $i < 5; $i++) {
            $this->borrowBookCopy($bookLoanFactory, $bookCopy, $dueDate);
        }
        $this
            ->shouldThrow(MaxNumberOfBookLoansExceededException::class)
            ->during('borrowBookCopy', [$bookLoanFactory, $bookCopy, $dueDate]);
    }

    function it_can_return_borrowed_book_and_the_ongoing_loan_is_removed(
        ReaderID $readerID,
        BookLoanFactory $bookLoanFactory,
        BookCopy $bookCopy,
        BookLoan $bookLoan,
        \DateTimeImmutable $dueDate,
        \DateTimeImmutable $endDate
    ) {
        $bookCopyID = new BookCopyID(1);
        $bookLoanID = new BookLoanID(1);
        $bookCopy->id()->willReturn($bookCopyID);
        $bookLoan->id()->willReturn($bookLoanID);
        $bookLoan->bookCopyID()->willReturn($bookCopyID);
        $bookLoanFactory->create($bookCopyID, $readerID, $dueDate)->willReturn($bookLoan);
        $this->borrowBookCopy($bookLoanFactory, $bookCopy, $dueDate);

        $remarks = '...';
        $bookLoan->endLoan($endDate, $remarks)->shouldBeCalled();
        $this->returnBookCopy($bookCopyID, $endDate, $remarks);

        $this->bookLoan($bookLoanID)->shouldBeNull();
    }

    function it_cannot_return_not_borrowed_book()
    {
        $bookCopyID = new BookCopyID(1);
        $endDate = new \DateTimeImmutable();

        $this
            ->shouldThrow(ReaderCannotReturnNotBorrowedBook::class)
            ->during('returnBookCopy', [$bookCopyID, $endDate]);
    }
}
