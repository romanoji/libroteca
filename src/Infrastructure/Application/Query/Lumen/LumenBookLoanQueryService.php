<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen;

use RJozwiak\Libroteca\Application\Query\BookLoanQueryService;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanNotFoundException;
use RJozwiak\Libroteca\Infrastructure\Persistence\Lumen;

class LumenBookLoanQueryService implements BookLoanQueryService
{
    /**
     * @param int|string $bookLoanID
     * @return array
     * @throws BookLoanNotFoundException
     */
    public function getOne($bookLoanID): array
    {
        $bookLoan = Lumen\Model\BookLoan::find($bookLoanID);

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
        $allowedFields = [
            'book_copy_id' => 'book_copy_id',
            'reader_id' => 'reader_id',
            'ended' => 'has_ended',
            'prolonged' => 'is_prolonged'
        ];

        $filtersValues = [];
        foreach ($filters as $field => $value) {
            if (array_key_exists($field, $allowedFields)) {
                $filtersValues[$allowedFields[$field]] = $value;
            }
        }

        return Lumen\Model\BookLoan::where($filtersValues)->get()->toArray();
    }
}
