<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\BookLoan;

use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookCopyAlreadyBorrowedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAttemptWhenHavingOverdueLoanException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\MaxOngoingLoansExceededException;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;

class BookLoanFactory
{
    /** @var BookLoanRepository */
    private $bookLoanRepository;

    /**
     * BookLoanFactory constructor.
     * @param BookLoanRepository $bookLoanRepository
     */
    public function __construct(BookLoanRepository $bookLoanRepository)
    {
        $this->bookLoanRepository = $bookLoanRepository;
    }

    /**
     * @param BookLoanID $id
     * @param BookCopyID $bookCopyID
     * @param ReaderID $readerID
     * @param \DateTimeImmutable $dueDate
     * @param \DateTimeImmutable $today
     * @return BookLoan
     * @throws BookCopyAlreadyBorrowedException
     * @throws BookLoanAttemptWhenHavingOverdueLoanException
     * @throws MaxOngoingLoansExceededException
     */
    public function create(
        BookLoanID $id,
        BookCopyID $bookCopyID,
        ReaderID $readerID,
        \DateTimeImmutable $dueDate,
        \DateTimeImmutable $today
    ){
        $this->assertBookCopyNotBorrowed($bookCopyID);
        $this->assertNotExceededMaxOngoingLoansForReader($readerID);
        $this->assertReaderCannotBorrowBooksWhenHavingOverdueLoan($readerID, $today);

        return new BookLoan($id, $bookCopyID, $readerID, $dueDate, $today);
    }

    /**
     * @param BookCopyID $bookCopyID
     * @throws BookCopyAlreadyBorrowedException
     */
    private function assertBookCopyNotBorrowed(BookCopyID $bookCopyID)
    {
        $bookLoan = $this->bookLoanRepository->findOngoingByBookCopyID($bookCopyID);

        if ($bookLoan !== null) {
            throw BookCopyAlreadyBorrowedException::fromBookCopyID($bookCopyID);
        }
    }

    /**
     * @param ReaderID $readerID
     * @throws MaxOngoingLoansExceededException
     */
    public function assertNotExceededMaxOngoingLoansForReader(ReaderID $readerID)
    {
        $bookLoans = $this->bookLoanRepository->findOngoingByReaderID($readerID); // TODO: cache it

        if (count($bookLoans) >= BookLoan::MAX_ONGOING_LOANS) {
            throw MaxOngoingLoansExceededException::fromReaderID($readerID);
        }
    }

    /**
     * @param ReaderID $readerID
     * @param \DateTimeImmutable $today
     * @throws BookLoanAttemptWhenHavingOverdueLoanException
     */
    public function assertReaderCannotBorrowBooksWhenHavingOverdueLoan(
        ReaderID $readerID,
        \DateTimeImmutable $today
    ) {
        $bookLoans = $this->bookLoanRepository->findOngoingByReaderID($readerID);

        foreach ($bookLoans as $bookLoan) {
            if ($bookLoan->isOverdue($today)) {
                throw BookLoanAttemptWhenHavingOverdueLoanException::fromReaderID($readerID);
            }
        }
    }
}
