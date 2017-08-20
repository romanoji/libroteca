<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen;

use RJozwiak\Libroteca\Application\Query\BookCopyQueryService;
use RJozwiak\Libroteca\Domain\Model\Book\Exception\BookNotFoundException;
use RJozwiak\Libroteca\Lumen;

class LumenBookCopyQueryService implements BookCopyQueryService
{
    /**
     * @param int|string $bookID
     * @param bool $includeOngoingLoans
     * @return array
     * @throws BookNotFoundException
     */
    public function getAllByBook($bookID, bool $includeOngoingLoans = false): array
    {
        $bookCopies =
            Lumen\Models\BookCopy::where('book_id', $bookID)
                ->keyBy('id')
                ->toArray();

        if ($includeOngoingLoans) {
            $bookCopiesIDs = array_keys($bookCopies);

            $bookLoans = Lumen\Models\BookLoan::whereIn('book_copy_id', $bookCopiesIDs)->toArray();

            foreach ($bookLoans as $bookLoan) {
                $bookCopyID = $bookLoan['book_copy_id'];

                $bookCopies[$bookCopyID]['ongoing_loan'] = $bookLoan;
            }
        }

        return $bookCopies;
    }
}
