<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen;

use RJozwiak\Libroteca\Application\Query\BookQueryService;
use RJozwiak\Libroteca\Domain\Model\Book\Exception\BookNotFoundException;
use RJozwiak\Libroteca\Lumen;

class LumenBookQueryService implements BookQueryService
{
    /**
     * @return array
     */
    public function getAll(): array
    {
        return Lumen\Models\Book::all()->toArray();
    }

    /**
     * @param int|string $bookID
     * @return array
     * @throws BookNotFoundException
     */
    public function getOne($bookID): array
    {
        $book = Lumen\Models\Book::find($bookID);

        if ($book === null) {
            throw new BookNotFoundException();
        }

        return $book->toArray();
    }
}
