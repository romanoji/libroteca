<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\Reader\{
    Email, Exception\ReaderNotFoundException, Phone, Reader, ReaderID, ReaderRepository
};

class InMemoryReaderRepository implements ReaderRepository
{
    /** @var int */
    private $nextID = 1;

    /** @var Reader[] */
    private $readers = [];

    /**
     * @return ReaderID
     */
    public function nextID(): ReaderID
    {
        return new ReaderID($this->nextID++);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->readers);
    }

    /**
     * @param Reader $reader
     */
    public function save(Reader $reader)
    {
        $this->readers[$reader->id()->id()] = $reader;
    }
    
    /**
     * @param ReaderID $id
     * @return Reader
     * @throws ReaderNotFoundException
     */
    public function get(ReaderID $id): Reader
    {
        if (!isset($this->readers[$id->id()])) {
            throw new ReaderNotFoundException();
        }

        return $this->readers[$id->id()];
    }

    /**
     * @param Email $email
     * @return null|Reader
     */
    public function findOneByEmail(Email $email): ?Reader
    {
        foreach ($this->readers as $reader) {
            if ($reader->email()->equals($email)) {
                return $reader;
            }
        }

        return null;
    }

    /**
     * @param Phone $phone
     * @return null|Reader
     */
    public function findOneByPhone(Phone $phone): ?Reader
    {
        foreach ($this->readers as $reader) {
            if ($reader->phone()->equals($phone)) {
                return $reader;
            }
        }

        return null;
    }
}
