<?php
declare(strict_types=1);

namespace Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Helper\ClearsBetweenScenarios;
use Helper\SharedObjects;
use Helper\SpiesOnExceptions;
use RJozwiak\Libroteca\Application\Command\RegisterReader;
use RJozwiak\Libroteca\Application\Command\RegisterReaderHandler;
use RJozwiak\Libroteca\Application\CommandBus;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\EmailAlreadyInUseException;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\PhoneAlreadyInUseException;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Reader\Reader;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderRepository;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\CommandHandlerResolver;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Inflector\ClassNameInflector;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Locator\InMemoryHandlerLocator;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\SimpleCommandBus;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\InMemoryReaderRepository;
use Webmozart\Assert\Assert;

class ReaderContext implements Context, SnippetAcceptingContext
{
    use SpiesOnExceptions, ClearsBetweenScenarios;

    /** @var CommandBus */
    private $commandBus;

    /** @var ReaderRepository */
    private $readerRepository;

    /** @var null|ReaderID */
    private $currentReaderID;

    public function __construct()
    {
        $this->readerRepository = SharedObjects::loadOrCreate(InMemoryReaderRepository::class);
        $this->commandBus = new SimpleCommandBus(
            new CommandHandlerResolver(
                new ClassNameInflector(),
                new InMemoryHandlerLocator([
                    new RegisterReaderHandler($this->readerRepository)
                ])
            )
        );
    }

    /**
     * @Given reader wants to register in library
     */
    public function noop()
    {
    }

    /**
     * @Given there is reader with email :email, name :name, surname :surname and phone :phone
     */
    public function createReaderFromData(
        string $name,
        string $surname,
        string $email,
        string $phone
    ) {
        $this->currentReaderID = $this->readerRepository->nextID();
        $this->commandBus->handle(
            new RegisterReader($this->currentReaderID->id(), $name, $surname, $email, $phone)
        );
    }

    /**
     * @When I (try to) register reader with his name :name, surname :surname, email :email and phone :phone
     */
    public function createReaderFromDataWithException(
        string $name,
        string $surname,
        string $email,
        string $phone
    ) {
        $this->spyOnException([$this, 'createReaderFromData'], [$name, $surname, $email, $phone]);
    }

    /**
     * @Then the reader should be registered
     */
    public function assertReaderIsRegistered()
    {
        Assert::isInstanceOf(
            $this->readerRepository->get($this->currentReaderID),
            Reader::class
        );
    }

    /**
     * @Then the reader should not be registered
     */
    public function assertCurrentReaderIsNotRegistered()
    {
        Assert::throws(
            function () { $this->readerRepository->get($this->currentReaderID); },
            ReaderNotFoundException::class
        );
    }

    /**
     * @Then I should be notified that specified email is already in use
     */
    public function assertEmailAlreadyInUseExceptionHasBeenThrown()
    {
        Assert::isInstanceOf($this->catchedException, EmailAlreadyInUseException::class);
        Assert::eq('Email is already in use.', $this->catchedException->getMessage());
    }

    /**
     * @Then I should be notified that specified phone is already in use
     */
    public function assertPhoneAlreadyInUseExceptionHasBeenThrown()
    {
        Assert::isInstanceOf($this->catchedException, PhoneAlreadyInUseException::class);
        Assert::eq('Phone is already in use.', $this->catchedException->getMessage());
    }

    /**
     * @Then I should be notified that specified email has invalid format
     */
    public function assertInvalidEmailFormatExceptionHasBeenThrown()
    {
        Assert::isInstanceOf($this->catchedException, \InvalidArgumentException::class);
        Assert::eq('Invalid email format.', $this->catchedException->getMessage());
    }

    /**
     * @Then I should be notified that specified phone has invalid format
     */
    public function assertInvalidPhoneFormatExceptionHasBeenThrown()
    {
        Assert::isInstanceOf($this->catchedException, \InvalidArgumentException::class);
        Assert::eq('Invalid phone format.', $this->catchedException->getMessage());
    }
}
