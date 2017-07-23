<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopy;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
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
        $this->ongoingBookLoan($bookLoanID)->shouldBeNull();
    }

    function it_can_borrow_a_book_copy_for_some_period_of_time(
        BookCopy $someBookCopy,
        BookCopy $otherBookCopy
    ) {
        $someBookCopyID = new BookCopyID(1);
        $otherBookCopyID = new BookCopyID(2);
        $someBookLoanID = new BookLoanID(1);
        $otherBookLoanID = new BookLoanID(2);
        $dueDate = new \DateTimeImmutable();
        $someBookCopy->id()->willReturn($someBookCopyID);
        $otherBookCopy->id()->willReturn($otherBookCopyID);

        $this->borrowBookCopy($someBookLoanID, $someBookCopy, $dueDate);
        $this->borrowBookCopy($otherBookLoanID, $otherBookCopy, $dueDate);

        $someBookLoan = $this->ongoingBookLoan($someBookLoanID);
        $otherBookLoan = $this->ongoingBookLoan($otherBookLoanID);
        $someBookLoan->bookCopyID()->shouldReturn($someBookCopyID);
        $otherBookLoan->bookCopyID()->shouldReturn($otherBookCopyID);
    }

    function it_throws_exception_when_borrowing_another_book_exceeds_max_loans_limit(
        BookLoanID $bookLoanID,
        BookCopyID $bookCopyID,
        BookCopy $bookCopy
    ) {
        $dueDate = new \DateTimeImmutable();
        $bookCopy->id()->willReturn($bookCopyID);

        for ($i = 0; $i < 5; $i++) {
            $this->borrowBookCopy($bookLoanID, $bookCopy, $dueDate);
        }

        $this
            ->shouldThrow(MaxNumberOfBookLoansExceededException::class)
            ->during('borrowBookCopy', [$bookLoanID, $bookCopy, $dueDate]);
    }

    function it_can_end_ongoing_loan_by_returning_borrowed_book(BookCopy $bookCopy)
    {
        $bookCopyID = new BookCopyID(1);
        $bookLoanID = new BookLoanID(1);
        $dueDate = new \DateTimeImmutable();
        $endDate = new \DateTimeImmutable();
        $bookCopy->id()->willReturn($bookCopyID);
        $this->borrowBookCopy($bookLoanID, $bookCopy, $dueDate);
        $bookLoan = $this->ongoingBookLoan($bookLoanID);

        $remarks = '...';
        $this->returnBookCopy($bookCopyID, $endDate, $remarks);

        $this->ongoingBookLoan($bookLoanID)->shouldBeNull();
        $bookLoan->hasEnded()->shouldBe(true);
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
