<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query;

use RJozwiak\Libroteca\Domain\Model\Book\Exception\BookNotFoundException;

interface BookCopyQueryService
{
    /**
     * @param int|string $bookID
     * @param bool $includeOngoingLoans
     * @return array
     * @throws BookNotFoundException
     */
    public function getAllByBook($bookID, bool $includeOngoingLoans = false) : array;
}
