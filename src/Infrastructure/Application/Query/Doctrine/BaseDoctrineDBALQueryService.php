<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine;

use RJozwiak\Libroteca\Domain\Model\AggregateNotFoundException;

abstract class BaseDoctrineDBALQueryService extends DoctrineDBALQueryService
{
    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->queryBuilder()
            ->select('*')
            ->from($this->tableName())
            ->execute()
            ->fetchAll();
    }

    /**
     * @param int|string $objectID
     * @return array
     * @throws AggregateNotFoundException
     */
    public function getOne($objectID): array
    {
        $object = $this->queryBuilder()
            ->select('*')
            ->from($this->tableName())
            ->where('id = ?')
            ->setParameter(0, $objectID)
            ->execute()
            ->fetch();

        if (!$object) {
            throw $this->notFoundException();
        }

        return $object;
    }

    /**
     * @return string
     */
    abstract protected function tableName(): string;

    /**
     * @return AggregateNotFoundException
     */
    abstract protected function notFoundException(): AggregateNotFoundException;
}
