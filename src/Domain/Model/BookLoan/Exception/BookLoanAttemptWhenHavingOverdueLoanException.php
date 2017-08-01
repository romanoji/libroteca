<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\BookLoan\Exception;

use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;

class BookLoanAttemptWhenHavingOverdueLoanException extends \RuntimeException
{
    /**
     * @param ReaderID $readerID
     * @return self
     */
    public static function fromReaderID(ReaderID $readerID)
    {
        return new self("Could not start book loan. Reader (id: {$readerID}) has ongoing overdue loan.");
    }
}
