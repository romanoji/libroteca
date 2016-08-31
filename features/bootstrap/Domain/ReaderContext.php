<?php

namespace Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Helper\CatchesException;
use Helper\SpiesOnExceptions;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\EmailAlreadyInUseException;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\PhoneAlreadyInUseException;
use RJozwiak\Libroteca\Domain\Model\Reader\Reader;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderRepository;
use RJozwiak\Libroteca\Domain\Model\Reader\RegisterReader;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\InMemoryReaderRepository;
use Webmozart\Assert\Assert;

class ReaderContext implements Context, SnippetAcceptingContext
{
    use SpiesOnExceptions;

    /** @var ReaderRepository */
    private $readerRepository;
    /** @var RegisterReader */
    private $registerReader;

    /** @var Reader */
    private $currentReader;

    public function __construct()
    {
        $this->readerRepository = new InMemoryReaderRepository();
        $this->registerReader = new RegisterReader($this->readerRepository);
    }

    /**
     * @Given reader wants to register in library
     */
    public function readerWantsToRegisterInLibrary()
    {
    }

    /**
     * @Given there is reader with email :email, name :name, surname :surname and phone :phone
     * @When I (try to) register reader with his name :name, surname :surname, email :email and phone :phone
     */
    public function iRegisterReaderByHisNameSurnameEmailAndPhone($name, $surname, $email, $phone)
    {
        $this->spyOnException(function () use ($name, $surname, $email, $phone) {
            $this->currentReader = $this->registerReader->execute($name, $surname, $email, $phone);
        });
    }

    /**
     * @Then the reader should be registered
     */
    public function theReaderShouldBeRegistered()
    {
        Assert::same(
            $this->readerRepository->find($this->currentReader->id()),
            $this->currentReader
        );
    }

    /**
     * @Then I should be notified that specified email is already in use
     */
    public function iShouldBeNotifiedThatSpecifiedEmailIsAlreadyInUse()
    {
        Assert::isInstanceOf($this->catchedException, EmailAlreadyInUseException::class);
    }

    /**
     * @Then I should be notified that specified phone is already in use
     */
    public function iShouldBeNotifiedThatSpecifiedPhoneIsAlreadyInUse()
    {
        Assert::isInstanceOf($this->catchedException, PhoneAlreadyInUseException::class);
    }

    /**
     * @Then I should be notified that specified email has invalid format
     */
    public function iShouldBeNotifiedThatSpecifiedEmailHasInvalidFormat()
    {
        Assert::isInstanceOf($this->catchedException, \InvalidArgumentException::class);
        Assert::eq($this->catchedException->getMessage(), 'Invalid email format.');
    }

    /**
     * @Then I should be notified that specified phone has invalid format
     */
    public function iShouldBeNotifiedThatSpecifiedPhoneHasInvalidFormat()
    {
        Assert::isInstanceOf($this->catchedException, \InvalidArgumentException::class);
        Assert::eq($this->catchedException->getMessage(), 'Invalid phone format.');
    }

    /**
     * @Then the reader should not be registered
     */
    public function theReaderShouldNotBeRegistered()
    {
        if ($this->currentReader !== null) {
            Assert::same($this->readerRepository->count(), 1);
        } else {
            Assert::same($this->readerRepository->count(), 0);
        }
    }
}
