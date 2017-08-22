<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen;

use RJozwiak\Libroteca\Application\Query\BookCopyQueryService;
use RJozwiak\Libroteca\Domain\Model\Book\Exception\BookNotFoundException;
use RJozwiak\Libroteca\Infrastructure\Persistence\Lumen;

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
            Lumen\Model\BookCopy::where('book_id', $bookID)
                ->get()
                ->keyBy('id')
                ->toArray();

        if ($includeOngoingLoans) {
            $bookCopiesIDs = array_keys($bookCopies);

            $bookLoans =
                Lumen\Model\BookLoan::whereIn('book_copy_id', $bookCopiesIDs)
                    ->get()
                    ->toArray();

            foreach ($bookLoans as $bookLoan) {
                $bookCopyID = $bookLoan['book_copy_id'];

                $bookCopies[$bookCopyID]['ongoing_loan'] = $bookLoan;
            }
        }

        return array_values($bookCopies);
    }
}
