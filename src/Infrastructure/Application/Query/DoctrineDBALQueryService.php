<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

abstract class DoctrineDBALQueryService
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return QueryBuilder
     */
    protected function queryBuilder()
    {
        return $this->entityManager->getConnection()->createQueryBuilder();
    }
}
