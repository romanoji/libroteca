<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader\BookLoan;

use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;

interface BookLoanFactory
{
    /**
     * @param BookCopyID $bookCopyID
     * @param ReaderID $readerID
     * @param \DateTimeImmutable $dueDate
     * @return BookLoan
     */
    public function create(
        BookCopyID $bookCopyID,
        ReaderID $readerID,
        \DateTimeImmutable $dueDate)
    : BookLoan;
}
