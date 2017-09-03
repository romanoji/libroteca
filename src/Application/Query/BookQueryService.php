<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query;

use RJozwiak\Libroteca\Application\Query\Pagination\Pagination;
use RJozwiak\Libroteca\Application\Query\Pagination\Results;
use RJozwiak\Libroteca\Application\Query\Specification\Specification;
use RJozwiak\Libroteca\Domain\Model\Book\Exception\BookNotFoundException;

interface BookQueryService
{
    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param Specification $criteria
     * @param Pagination $pagination
     * @return Results
     */
    public function getAllByCriteria(
        ?Specification $criteria,
        Pagination $pagination
    ): Results;

    /**
     * @param int|string $bookID
     * @return array
     * @throws BookNotFoundException
     */
    public function getOne($bookID): array;
}
