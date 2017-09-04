<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RJozwiak\Libroteca\Application\Query\BookQueryService;
use RJozwiak\Libroteca\Application\Query\Pagination\Metadata as PaginationMetadata;
use RJozwiak\Libroteca\Application\Query\Pagination\Pagination;
use RJozwiak\Libroteca\Application\Query\Pagination\Results;
use RJozwiak\Libroteca\Application\Query\Specification\Specification;
use RJozwiak\Libroteca\Domain\Model\Book\Exception\BookNotFoundException;
use RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen\Specification\ExpressionFactory;
use RJozwiak\Libroteca\Infrastructure\Persistence\Lumen;

class LumenBookQueryService implements BookQueryService
{
    /** @var ExpressionFactory */
    private $expressionFactory;

    /**
     * @param ExpressionFactory $expressionFactory
     */
    public function __construct(ExpressionFactory $expressionFactory)
    {
        $this->expressionFactory = $expressionFactory;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return Lumen\Model\Book::all()->toArray();
    }

    /**
     * @param int|string $bookID
     * @return array
     * @throws BookNotFoundException
     */
    public function getOne($bookID): array
    {
        $book = Lumen\Model\Book::find($bookID);

        if ($book === null) {
            throw new BookNotFoundException();
        }

        return $book->toArray();
    }

    /**
     * @param Specification $criteria
     * @param Pagination $pagination
     * @return Results
     */
    public function getAllByCriteria(
        ?Specification $criteria,
        Pagination $pagination
    ): Results
    {
        if ($criteria !== null) {
            $expr = $criteria->toExpression($this->expressionFactory)->value();

            $paginator = Lumen\Model\Book::where($expr)
                ->paginate($pagination->resultsCount(), ['*'], 'page', $pagination->page());
        } else {
            $paginator = Lumen\Model\Book::paginate($pagination->resultsCount(), ['*'], 'page', $pagination->page());
        }

        return new Results(
            $paginator->items(),
            $this->createPaginationMetadata($paginator)
        );
    }

    /**
     * @param LengthAwarePaginator $paginator
     * @return PaginationMetadata
     */
    private function createPaginationMetadata(LengthAwarePaginator $paginator): PaginationMetadata
    {
        return new PaginationMetadata(
            $paginator->currentPage(),
            $paginator->perPage(),
            count($paginator->items()),
            $paginator->total()
        );
    }
}
