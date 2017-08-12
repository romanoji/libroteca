<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use RJozwiak\Libroteca\Application\Query\BookLoanQueryService;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoan;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanNotFoundException;

class DoctrineBookLoanQueryService extends DoctrineQueryService implements BookLoanQueryService
{
    /**
     * @param int|string $bookLoanID
     * @return array
     * @throws BookLoanNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getOne($bookLoanID): array
    {
        try {
            $bookLoan = $this->queryBuilder()
                ->select('bl')
                ->from(BookLoan::class, 'bl')
                ->where('bl.id = :id')
                ->setParameter('id', new BookLoanID($bookLoanID))
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            throw new BookLoanNotFoundException();
        }

        return $this->serializer->toArray($bookLoan);
    }

    /**
     * @return QueryBuilder
     */
    protected function queryBuilder()
    {
        return $this->entityManager->createQueryBuilder();
    }
}
