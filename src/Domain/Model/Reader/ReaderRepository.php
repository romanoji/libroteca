<?php

namespace RJozwiak\Libroteca\Domain\Model\Reader;

interface ReaderRepository
{
    // TODO: find -> get

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
    public function count();

    /**
     * @param ReaderID $id
     * @return null|Reader
     */
    public function find(ReaderID $id);

    /**
     * @param Email $email
     * @return null|Reader
     */
    public function findOneByEmail(Email $email);

    /**
     * @param Phone $phone
     * @return null|Reader
     */
    public function findOneByPhone(Phone $phone);
}
