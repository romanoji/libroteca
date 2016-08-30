<?php

namespace RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\Reader\Exception\EmailAlreadyInUseException;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\PhoneAlreadyInUseException;

class RegisterReader
{
    /** @var ReaderRepository */
    private $readerRepository;

    /**
     * RegisterReader constructor.
     * @param ReaderRepository $readerRepository
     */
    public function __construct(ReaderRepository $readerRepository)
    {
        $this->readerRepository = $readerRepository;
    }

    /**
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $phone
     * @return Reader
     * @throws EmailAlreadyInUseException
     * @throws PhoneAlreadyInUseException
     */
    public function execute($name, $surname, $email, $phone)
    {
        $this->assertUniqueEmail($email);
        $this->assertUniquePhone($phone);

        $reader = new Reader(
            $this->readerRepository->nextID(),
            new Name($name),
            new Surname($surname),
            new Email($email),
            new Phone($phone)
        );

        $this->readerRepository->add($reader);

        // TODO: ReaderRegistered event

        return $reader;
    }

    /**
     * @param string $email
     * @throws EmailAlreadyInUseException
     */
    private function assertUniqueEmail($email)
    {
        $reader = $this->readerRepository->findOneByEmail(
            new Email($email)
        );

        if ($reader instanceof Reader) {
            throw new EmailAlreadyInUseException('Email is already in use.');
        }
    }

    /**
     * @param string $phone
     * @throws PhoneAlreadyInUseException
     */
    private function assertUniquePhone($phone)
    {
        $reader = $this->readerRepository->findOneByPhone(
            new Phone($phone)
        );

        if ($reader instanceof Reader) {
            throw new PhoneAlreadyInUseException('Phone is already in use.');
        }
    }
}
