<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader;

use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Domain\Model\Reader\Email;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Reader\Phone;
use RJozwiak\Libroteca\Domain\Model\Reader\Reader;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderRepository;

class DoctrineReaderRepository extends EntityRepository implements ReaderRepository
{
    /**
     * @return ReaderID
     */
    public function nextID(): ReaderID
    {
        return new ReaderID(Uuid::uuid4()->toString());
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function count(): int
    {
        $qb = $this->createQueryBuilder('r');

        return (int) $qb->select($qb->expr()->count('r'))->getQuery()->getSingleResult();
    }

    /**
     * @param Reader $reader
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function save(Reader $reader)
    {
        $this->getEntityManager()->persist($reader);
    }

    /**
     * @param ReaderID $id
     * @return Reader
     * @throws ReaderNotFoundException
     */
    public function get(ReaderID $id): Reader
    {
        $reader = $this->find($id->id());

        if (!isset($reader)) {
            throw new ReaderNotFoundException();
        }

        /** @var Reader $reader */
        return $reader;
    }

    /**
     * @param Email $email
     * @return null|Reader
     */
    public function findOneByEmail(Email $email): ?Reader
    {
        return $this->findOneBy(['email' => $email->email()]);
    }

    /**
     * @param Phone $phone
     * @return null|Reader
     */
    public function findOneByPhone(Phone $phone): ?Reader
    {
        return $this->findOneBy(['phone' => $phone->phone()]);
    }
}
