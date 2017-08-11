<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query;

use RJozwiak\Libroteca\Domain\Model\Book\Exception\BookNotFoundException;

interface BookQueryService
{
    /**
     * @return array
     */
    public function getAll() : array;

    /**
     * @param int|string $bookID
     * @return array
     * @throws BookNotFoundException
     */
    public function getOne($bookID) : array;
}
