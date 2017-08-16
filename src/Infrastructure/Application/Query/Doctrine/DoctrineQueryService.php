<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JMS\Serializer\Serializer;

abstract class DoctrineQueryService
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var Serializer */
    protected $serializer;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Serializer $serializer
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Serializer $serializer
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * @return QueryBuilder
     */
    protected function queryBuilder()
    {
        return $this->entityManager->createQueryBuilder();
    }
}
