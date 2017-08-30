<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query;

use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanNotFoundException;

interface BookLoanQueryService
{
    /**
     * @param int|string $bookLoanID
     * @return array
     * @throws BookLoanNotFoundException
     */
    public function getOne($bookLoanID): array;

    /**
     * @param array $filters
     * @return array
     */
    public function getAll(array $filters): array;
}
