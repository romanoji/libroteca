<?php
declare(strict_types=1);

namespace Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Helper\SpiesOnExceptions;
use RJozwiak\Libroteca\Application\Command\LendBookCopy;
use RJozwiak\Libroteca\Application\Command\LendBookCopyHandler;
use RJozwiak\Libroteca\Application\Command\ProlongBookLoan;
use RJozwiak\Libroteca\Application\CommandBus;
use RJozwiak\Libroteca\Domain\Model\Book\BookRepository;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBNFactory;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyRepository;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderRepository;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\CommandHandlerResolver;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Inflector\ClassNameInflector;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Locator\InMemoryHandlerLocator;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\SimpleCommandBus;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Book\InMemoryBookRepository;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\BookCopy\InMemoryBookCopyRepository;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\InMemoryReaderRepository;
use Webmozart\Assert\Assert;

class LoanContext implements Context, SnippetAcceptingContext
{
    use SpiesOnExceptions;

    /** @var CommandBus */
    private $commandBus;

    /** @var ISBNFactory */
    private $isbnFactory;
    /** @var ReaderRepository */
    private $readerRepository;
    /** @var BookRepository */
    private $bookRepository;
    /** @var BookCopyRepository */
    private $bookCopyRepository;

    /** @var ReaderID */
    private $currentReaderID;
    /** @var BookCopyID */
    private $currentBookCopyID;

    public function __construct()
    {
        $this->isbnFactory = new ISBNFactory();
        $this->readerRepository = new InMemoryReaderRepository();
        $this->bookRepository = new InMemoryBookRepository();
        $this->bookCopyRepository = new InMemoryBookCopyRepository();
        $this->commandBus = new SimpleCommandBus(
            new CommandHandlerResolver(
                new ClassNameInflector(),
                new InMemoryHandlerLocator([
                    new LendBookCopyHandler($this->readerRepository, $this->bookCopyRepository)
                    // ...
                ])
            )
        );
    }

    /**
     * @Given there is a loan for a book copy with ISBN :isbn to reader with email :email till :till
     */
    public function thereIsALoanForABookOfIsbnToReaderWithEmailTill($isbn, $email, $till)
    {
        // handle "day after tomorrow"
        $till = $till === 'day after tomorrow' ? 'tomorrow + 1 day' : $till;

        $reader = $this->readerRepository->findOneByEmail($email);
        $book = $this->bookRepository->findOneByISBN(
            $this->isbnFactory->create($isbn)
        );
        $bookCopies = $this->bookCopyRepository->findByBookID($book->id());
        Assert::greaterThan(count($bookCopies), 0);
        $this->currentBookCopyID = $bookCopies[0]->id();

        $this->commandBus->handle(
            new LendBookCopy(
                $reader->id()->id(),
                $this->currentBookCopyID->id(),
                (new \DateTimeImmutable($till))->format('d-m-Y')
            )
        );
    }

    /**
     * @Given this loan has already been prolonged
     */
    public function thisLoanHasAlreadyBeenProlonged()
    {
        throw new PendingException();
    }

    /**
     * @When I lend a book copy with ISBN :isbn to the reader with email :email for :days days
     */
    public function iLendBookCopyWithIsbnToTheReaderWithEmailForDays($isbn, $email, $days)
    {
        $this->currentReaderID = $this->readerRepository->findOneByEmail($email)->id();
        $book = $this->bookRepository->findOneByISBN(
            $this->isbnFactory->create($isbn)
        );
        $bookCopies = $this->bookCopyRepository->findByBookID($book->id());
        Assert::greaterThan(count($bookCopies), 0);
        $this->currentBookCopyID = $bookCopies[0]->id();

        $this->commandBus->handle(
            new LendBookCopy(
                $this->currentReaderID->id(),
                $this->currentBookCopyID->id(),
                (new \DateTimeImmutable("+ $days day"))->format('d-m-Y')
            )
        );
    }

    /**
     * @When I lend books copies to reader with email :email:
     */
    public function iLendBooksCopiesToReaderWithEmail($email, TableNode $booksLoans)
    {
        $this->currentReaderID = $this->readerRepository->findOneByEmail($email)->id();

        foreach ($booksLoans as $bookLoan) {
            $book = $this->bookRepository->findOneByISBN(
                $this->isbnFactory->create($bookLoan['isbn'])
            );
            $bookCopies = $this->bookCopyRepository->findByBookID($book->id());
            Assert::greaterThan(count($bookCopies), 0);
            $bookCopyID = $bookCopies[0]->id();
            $days = $bookLoan['for N days'];

            $this->commandBus->handle(
                new LendBookCopy(
                    $this->currentReaderID->id(),
                    $bookCopyID,
                    (new \DateTimeImmutable("+ $days day"))->format('d-m-Y')
                )
            );
        }
    }

    /**
     * @When I lend the same book copy to the reader with email :email for :days days
     */
    public function iLendTheSameBookCopyToTheReaderWithEmailForDays($email, $days)
    {
        $readerID = $this->readerRepository->findOneByEmail($email)->id();

        $this->commandBus->handle(
            new LendBookCopy(
                $readerID,
                $this->currentBookCopyID,
                (new \DateTimeImmutable("+ $days day"))->format('d-m-Y')
            )
        );
    }

    /**
     * @When I prolong loan period on this book copy by :days days
     */
    public function iProlongLoanPeriodOnThisBookCopyByDays($days)
    {
        throw new PendingException();
    }

    /**
     * @When I accept a book copy identified by ISBN :isbn from a reader with email :email without remarks
     */
    public function iAcceptABookCopyIdentifiedByIsbnFromAReaderWithEmailWithoutRemarks($isbn, $email)
    {
        throw new PendingException();
    }

    /**
     * @When I accept a book copy identified by ISBN :isbn from a reader with email :email with remarks:
     */
    public function iAcceptABookCopyIdentifiedByIsbnFromAReaderWithEmailWithRemarks(
        $isbn,
        $email,
        PyStringNode $remarks
    ) {
        throw new PendingException();
    }

    /**
     * @Then there should be book loan for a reader with email :email for :days days
     */
    public function readerWithEmailShouldHaveThatBookCopyLentForDays($email, $days)
    {
        throw new PendingException();
    }

    /**
     * @Then the loan attempt should not be successful
     */
    public function theLoanAttemptShouldNotBeSuccessful()
    {
        throw new PendingException();
    }

    /**
     * @Then loan attempt for book copy with ISBN :isbn should not be successful
     */
    public function loanAttemptForBookCopyWithIsbnShouldNotBeSuccessful($isbn)
    {
        throw new PendingException();
    }

    /**
     * @Then this loan should expire :days days later
     */
    public function thisLoanShouldExpireDaysLater($days)
    {
        throw new PendingException();
    }

    /**
     * @Then this loan period should not be prolonged
     */
    public function thisLoanPeriodShouldNotBeProlonged()
    {
        throw new PendingException();
    }

    /**
     * @Then this book copy should be returned
     */
    public function thisBookCopyShouldBeReturned()
    {
        throw new PendingException();
    }

    /**
     * @Then this book copy should not be returned
     */
    public function thisBookCopyShouldNotBeReturned()
    {
        throw new PendingException();
    }

    /**
     * @Then the reader should be notified on email :email about loan expiration with message:
     */
    public function theReaderShouldBeNotifiedOnEmailAboutLoanExpirationWithMessage(
        $email,
        PyStringNode $message
    ) {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that book copy is already lent
     */
    public function iShouldBeNotifiedThatBookCopyIsAlreadyLent()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that reader must return books first to proceed with loan attempt
     */
    public function iShouldBeNotifiedThatReaderMustReturnBooksFirstToProceedWithLoanAttempt()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that the loan period has been already prolonged
     */
    public function iShouldBeNotifiedThatTheLoanPeriodHasBeenAlreadyProlonged()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that the loan period has already expired
     */
    public function iShouldBeNotifiedThatTheLoanPeriodHasAlreadyExpired()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that loan period can last at most for :days days
     */
    public function iShouldBeNotifiedThatLoanPeriodCanLastAtMostForDays($days)
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that the loan prolongation is at most :days days
     */
    public function iShouldBeNotifiedThatTheLoanProlongationIsAtMostDays($days)
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that adding remarks to overdue loan is required
     */
    public function iShouldBeNotifiedThatAddingRemarksToOverdueLoanIsRequired()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that reader has reached limit of borrowed books
     */
    public function iShouldBeNotifiedThatReaderHasReachedLimitOfBorrowedBooks()
    {
        throw new PendingException();
    }
}
