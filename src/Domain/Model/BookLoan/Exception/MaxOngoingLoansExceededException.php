<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\BookLoan\Exception;

use RJozwiak\Libroteca\Domain\Model\DomainLogicException;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;

class MaxOngoingLoansExceededException extends DomainLogicException
{
    /**
     * @param ReaderID $readerID
     * @return self
     */
    public static function fromReaderID(ReaderID $readerID) : self
    {
        return new self("Max ongoing loans exceeded for reader with id `$readerID`.");
    }
}
