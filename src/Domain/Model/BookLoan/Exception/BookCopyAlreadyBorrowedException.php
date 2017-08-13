<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\BookLoan\Exception;

use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\DomainLogicException;

class BookCopyAlreadyBorrowedException extends DomainLogicException
{
    /**
     * @param BookCopyID $bookCopyID
     * @return self
     */
    public static function fromBookCopyID(BookCopyID $bookCopyID) : self
    {
        return new self("Book copy (id: $bookCopyID) is already borrowed.");
    }
}
