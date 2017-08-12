<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query;

interface BookCopyQueryService
{
    /**
     * @param int|string $bookID
     * @return array
     */
    public function getAllByBook($bookID) : array;
}
