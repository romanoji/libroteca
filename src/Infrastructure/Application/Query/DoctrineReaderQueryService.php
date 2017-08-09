<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query;

use Doctrine\ORM\EntityManagerInterface;
use RJozwiak\Libroteca\Application\Query\ReaderQueryService;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderNotFoundException;

class DoctrineReaderQueryService implements ReaderQueryService
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
            ->fetchAll('SELECT * FROM readers');
    }

    /**
     * @param $readerID
     * @return array
     * @throws ReaderNotFoundException
     */
    public function getOne($readerID) : array
    {
        $reader = $this->entityManager
            ->getConnection()
            ->fetchAssoc(
                'SELECT * FROM readers WHERE id = ?',
                [$readerID]
            );

        if (!$reader) {
            throw new ReaderNotFoundException();
        }

        return $reader;
    }
}
