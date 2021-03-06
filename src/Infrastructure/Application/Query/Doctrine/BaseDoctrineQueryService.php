<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use RJozwiak\Libroteca\Domain\Model\AggregateNotFoundException;

abstract class BaseDoctrineQueryService extends DoctrineQueryService
{
    /**
     * @return array
     */
    public function getAll(): array
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
     * @throws NonUniqueResultException
     */
    public function getOne($objectID): array
    {
        try {
            $object = $this->queryBuilder()
                ->select('t')
                ->from($this->resourceClassName(), 't')
                ->where('t.id = :id')
                ->setParameter('id', $this->createID($objectID))
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            throw $this->notFoundException();
        }

        return $this->serializer->toArray($object);
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
    abstract protected function resourceClassName(): string;

    /**
     * @return string
     */
    abstract protected function resourceIDClassName(): string;

    /**
     * @return AggregateNotFoundException
     */
    abstract protected function notFoundException(): AggregateNotFoundException;

}
