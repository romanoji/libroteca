<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JMS\Serializer\Serializer;
use RJozwiak\Libroteca\Application\Query\BookQueryService;
use RJozwiak\Libroteca\Application\Query\Pagination\Metadata as PaginationMetadata;
use RJozwiak\Libroteca\Application\Query\Pagination\Pagination;
use RJozwiak\Libroteca\Application\Query\Pagination\Results;
use RJozwiak\Libroteca\Application\Query\Specification\Specification;
use RJozwiak\Libroteca\Domain\Model\AggregateNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Book\Book;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\Book\Exception\BookNotFoundException;
use RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine\Specification\ExpressionFactory;

class DoctrineBookQueryService extends BaseDoctrineQueryService implements BookQueryService
{
    /** @var ExpressionFactory */
    private $expressionFactory;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Serializer $serializer
     * @param ExpressionFactory $expressionFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Serializer $serializer,
        ExpressionFactory $expressionFactory
    ) {
        parent::__construct($entityManager, $serializer);

        $this->expressionFactory = $expressionFactory;
    }

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

    /**
     * @param null|Specification $criteria
     * @param Pagination $pagination
     * @return Results
     */
    public function getAllByCriteria(
        ?Specification $criteria,
        Pagination $pagination
    ): Results {
        $qb = $this->queryBuilder()
            ->select('t')
            ->from($this->resourceClassName(), 't');

        if ($criteria !== null) {
            $qb->where(
                $criteria->toExpression($this->expressionFactory)->value()
            );
        }

        $books = $qb
            ->setFirstResult(($pagination->page() - 1) * $pagination->resultsCount())
            ->setMaxResults($pagination->resultsCount())
            ->getQuery()
            ->getResult();

        return new Results(
            $this->serializer->toArray($books),
            $this->createPaginationMetadata($pagination, $qb, count($books))
        );
    }

    /**
     * @param Pagination $pagination
     * @param QueryBuilder $qb
     * @param int $count
     * @return PaginationMetadata
     */
    private function createPaginationMetadata(
        Pagination $pagination,
        QueryBuilder $qb,
        int $count
    ): PaginationMetadata {
        return new PaginationMetadata(
            $pagination->page(),
            $pagination->resultsCount(),
            $count,
            $this->count($qb)
        );
    }

    /**
     * @param QueryBuilder $qb
     * @return int
     */
    private function count(QueryBuilder $qb): int
    {
        return (int) $qb->select($qb->expr()->count('t'))->getQuery()->getSingleScalarResult();
    }
}
