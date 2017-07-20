<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader;

interface ReaderRepository
{
    /**
     * @return ReaderID
     */
    public function nextID();

    /**
     * @param Reader $reader
     */
    public function add(Reader $reader);

    /**
     * @return int
     */
    public function count() : int;

    /**
     * @param ReaderID $id
     * @return null|Reader
     */
    public function find(ReaderID $id) : ?Reader;

    /**
     * @param Email $email
     * @return null|Reader
     */
    public function findOneByEmail(Email $email) : ?Reader;

    /**
     * @param Phone $phone
     * @return null|Reader
     */
    public function findOneByPhone(Phone $phone) : ?Reader;
}
