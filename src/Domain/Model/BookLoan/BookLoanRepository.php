<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\BookLoan;

use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;

interface BookLoanRepository
{
    /**
     * @return BookLoanID
     */
    public function nextID(): BookLoanID;

    /**
     * @param BookLoan $bookLoan
     */
    public function save(BookLoan $bookLoan);

    /**
     * @param BookLoanID $id
     * @return BookLoan
     * @throws BookLoanNotFoundException
     */
    public function get(BookLoanID $id): BookLoan;

    /**
     * @param BookCopyID $bookCopyID
     * @return null|BookLoan
     */
    public function findOngoingByBookCopyID(BookCopyID $bookCopyID): ?BookLoan;

    /**
     * @param ReaderID $readerID
     * @return BookLoan[]
     */
    public function findOngoingByReaderID(ReaderID $readerID): array;
}
