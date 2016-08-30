<?php

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\Reader\Email;
use RJozwiak\Libroteca\Domain\Model\Reader\Phone;
use RJozwiak\Libroteca\Domain\Model\Reader\Reader;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderRepository;

class InMemoryReaderRepository implements ReaderRepository
{
    /** @var int */
    private $nextID = 1;

    /** @var Reader[] */
    private $readers = [];

    /**
     * @return ReaderID
     */
    public function nextID()
    {
        return new ReaderID($this->nextID++);
    }

    /**
     * @param Reader $reader
     */
    public function add(Reader $reader)
    {
        $this->readers[$reader->id()->id()] = $reader;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->readers);
    }
    
    /**
     * @param ReaderID $id
     * @return null|Reader
     */
    public function find(ReaderID $id)
    {
        if (!isset($this->readers[$id->id()])) {
            return null;
        }

        return $this->readers[$id->id()];
    }

    /**
     * @param Email $email
     * @return null|Reader
     */
    public function findOneByEmail(Email $email)
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
    public function findOneByPhone(Phone $phone)
    {
        foreach ($this->readers as $reader) {
            if ($reader->phone()->equals($phone)) {
                return $reader;
            }
        }

        return null;
    }
}
