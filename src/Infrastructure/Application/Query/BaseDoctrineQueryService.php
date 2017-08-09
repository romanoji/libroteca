<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query;

use Doctrine\ORM\EntityManagerInterface;
use RJozwiak\Libroteca\Domain\Model\AggregateNotFoundException;

abstract class BaseDoctrineQueryService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getAll() : array
    {
        return $this->entityManager
            ->getConnection()
            ->fetchAll(sprintf('SELECT * FROM %s', $this->tableName()));
    }

    /**
     * @param $objectID
     * @return array
     * @throws AggregateNotFoundException
     */
    public function getOne($objectID) : array
    {
        $object = $this->entityManager
            ->getConnection()
            ->fetchAssoc(
                sprintf('SELECT * FROM %s WHERE id = ?', $this->tableName()),
                [$objectID]
            );

        if (!$object) {
            throw $this->notFoundException();
        }

        return $object;
    }

    /**
     * @return string
     */
    abstract protected function tableName() : string;

    /**
     * @return AggregateNotFoundException
     */
    abstract protected function notFoundException() : AggregateNotFoundException;
}
