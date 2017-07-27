<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderNotFoundException;

interface ReaderRepository
{
    /**
     * @return ReaderID
     */
    public function nextID() : ReaderID;

    /**
     * @return int
     */
    public function count() : int;

    /**
     * @param Reader $reader
     */
    public function save(Reader $reader);

    /**
     * @param ReaderID $id
     * @return Reader
     * @throws ReaderNotFoundException
     */
    public function get(ReaderID $id) : Reader;

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
