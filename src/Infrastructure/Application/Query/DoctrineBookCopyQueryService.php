<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query;

use Doctrine\ORM\QueryBuilder;
use RJozwiak\Libroteca\Application\Query\BookCopyQueryService;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopy;

class DoctrineBookCopyQueryService extends DoctrineQueryService implements BookCopyQueryService
{
    /**
     * @param int|string $bookID
     * @return array
     */
    public function getAllByBook($bookID): array
    {
        $bookCopies = $this->queryBuilder()
            ->select('bc')
            ->from(BookCopy::class, 'bc')
            ->where('bc.bookID = :bookID')
            ->setParameter('bookID', new BookID($bookID))
            ->getQuery()
            ->getResult();

        return $this->serializer->toArray($bookCopies);
    }

    /**
     * @return QueryBuilder
     */
    protected function queryBuilder()
    {
        return $this->entityManager->createQueryBuilder();
    }
}
