<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\BookLoan;

use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoan;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanRepository;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;

class InMemoryBookLoanRepository implements BookLoanRepository
{
    /** @var int */
    private $nextID = 1;

    /** @var BookLoan[] */
    private $booksLoans = [];

    /**
     * @return BookLoanID
     */
    public function nextID() : BookLoanID
    {
        return new BookLoanID($this->nextID++);
    }

    /**
     * @param BookLoan $bookLoan
     */
    public function save(BookLoan $bookLoan)
    {
        $this->booksLoans[$bookLoan->id()->id()] = $bookLoan;
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->booksLoans);
    }

    /**
     * @param BookLoanID $id
     * @return BookLoan
     * @throws BookLoanNotFoundException
     */
    public function get(BookLoanID $id) : BookLoan
    {
        if (!isset($this->booksLoans[$id->id()])) {
            throw new BookLoanNotFoundException();
        }

        return $this->booksLoans[$id->id()];
    }

    /**
     * @param BookCopyID $bookCopyID
     * @return null|BookLoan
     */
    public function findOngoingByBookCopyID(BookCopyID $bookCopyID): ?BookLoan
    {
        foreach ($this->booksLoans as $bookLoan) {
            if ($bookLoan->bookCopyID()->equals($bookCopyID) && !$bookLoan->hasEnded()) {
                return $bookLoan;
            }
        }

        return null;
    }

    /**
     * @param ReaderID $readerID
     * @return BookLoan[]
     */
    public function findOngoingByReaderID(ReaderID $readerID): array
    {
        $booksLoans = [];

        foreach ($this->booksLoans as $bookLoan) {
            if ($bookLoan->readerID()->equals($readerID) && !$bookLoan->hasEnded()) {
                $booksLoans[] = $bookLoan;
            }
        }

        return $booksLoans;
    }
}
