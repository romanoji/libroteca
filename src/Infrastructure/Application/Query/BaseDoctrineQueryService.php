<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JMS\Serializer\Serializer;
use RJozwiak\Libroteca\Domain\Model\AggregateNotFoundException;

abstract class BaseDoctrineQueryService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var Serializer */
    private $serializer;

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
     * @return array
     */
    public function getAll() : array
    {
        $objects = $this->queryBuilder()
            ->select('t')
            ->from($this->resourceClassName(), 't')
            ->getQuery()
            ->getResult();

        return $this->serializer->toArray($objects);
    }

    /**
     * @param int|string $objectID
     * @return array
     * @throws AggregateNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getOne($objectID) : array
    {
        $object = $this->queryBuilder()
            ->select('t')
            ->from($this->resourceClassName(), 't')
            ->where('t.id = :id')
            ->setParameter('id', $this->createID($objectID))
            ->getQuery()
            ->getSingleResult();

        if (!$object) {
            throw $this->notFoundException();
        }

        return $this->serializer->toArray($object);
    }

    /**
     * @return QueryBuilder
     */
    protected function queryBuilder()
    {
        return $this->entityManager->createQueryBuilder();
    }

    /**
     * @param int|string $id
     * @return mixed
     */
    private function createID($id)
    {
        $className = $this->resourceIDClassName();

        return new $className($id);
    }

    /**
     * @return string
     */
    abstract protected function resourceClassName() : string;

    /**
     * @return string
     */
    abstract protected function resourceIDClassName() : string;

    /**
     * @return AggregateNotFoundException
     */
    abstract protected function notFoundException() : AggregateNotFoundException;

}
