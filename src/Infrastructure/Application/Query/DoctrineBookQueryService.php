<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query;

use RJozwiak\Libroteca\Application\Query\BookQueryService;
use RJozwiak\Libroteca\Domain\Model\AggregateNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Book\Book;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\Book\Exception\BookNotFoundException;

class DoctrineBookQueryService extends BaseDoctrineQueryService implements BookQueryService
{
    /**
     * @return string
     */
    protected function resourceClassName(): string
    {
        return Book::class;
    }

    /**
     * @return string
     */
    protected function resourceIDClassName(): string
    {
        return BookID::class;
    }

    /**
     * @return AggregateNotFoundException
     */
    protected function notFoundException(): AggregateNotFoundException
    {
        return new BookNotFoundException();
    }
}
