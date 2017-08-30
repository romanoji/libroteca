<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Domain\Model\Reader\Email;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\EmailAlreadyInUseException;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\PhoneAlreadyInUseException;
use RJozwiak\Libroteca\Domain\Model\Reader\Name;
use RJozwiak\Libroteca\Domain\Model\Reader\Phone;
use RJozwiak\Libroteca\Domain\Model\Reader\Reader;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderRepository;
use RJozwiak\Libroteca\Domain\Model\Reader\Surname;

class RegisterReaderHandler implements CommandHandler
{
    /** @var ReaderRepository */
    private $readerRepository;

    /**
     * @param ReaderRepository $readerRepository
     */
    public function __construct(ReaderRepository $readerRepository)
    {
        $this->readerRepository = $readerRepository;
    }

    /**
     * @param RegisterReader $command
     * @throws EmailAlreadyInUseException
     * @throws PhoneAlreadyInUseException
     */
    public function execute(RegisterReader $command) : void
    {
        $readerID = new ReaderID($command->readerID);
        $name = new Name($command->name);
        $surname = new Surname($command->surname);
        $email = new Email($command->email);
        $phone = new Phone($command->phone);

        $this->assertUniqueEmail($email);
        $this->assertUniquePhone($phone);

        $reader = new Reader($readerID, $name, $surname, $email, $phone);
        $this->readerRepository->save($reader);
    }

    /**
     * @param Email $email
     * @throws EmailAlreadyInUseException
     */
    private function assertUniqueEmail(Email $email)
    {
        $reader = $this->readerRepository->findOneByEmail($email);

        if ($reader instanceof Reader) {
            throw new EmailAlreadyInUseException('Email is already in use.');
        }
    }

    /**
     * @param Phone $phone
     * @throws PhoneAlreadyInUseException
     */
    private function assertUniquePhone(Phone $phone)
    {
        $reader = $this->readerRepository->findOneByPhone($phone);

        if ($reader instanceof Reader) {
            throw new PhoneAlreadyInUseException('Phone is already in use.');
        }
    }
}
