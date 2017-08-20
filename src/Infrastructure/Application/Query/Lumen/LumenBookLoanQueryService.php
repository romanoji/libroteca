<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen;

use RJozwiak\Libroteca\Application\Query\BookLoanQueryService;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanNotFoundException;
use RJozwiak\Libroteca\Lumen;

class LumenBookLoanQueryService implements BookLoanQueryService
{
    /**
     * @param int|string $bookLoanID
     * @return array
     * @throws BookLoanNotFoundException
     */
    public function getOne($bookLoanID): array
    {
        $bookLoan = Lumen\Models\BookLoan::find($bookLoanID);

        if ($bookLoan === null) {
            throw new BookLoanNotFoundException();
        }

        return $bookLoan->toArray();
    }

    /**
     * @param array $filters
     * @return array
     */
    public function getAll(array $filters): array
    {
        return Lumen\Models\BookLoan::all()->toArray();
    }
}
