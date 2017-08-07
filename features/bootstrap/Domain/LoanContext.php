<?php
declare(strict_types=1);

namespace Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Helper\ClearsBetweenScenarios;
use Helper\SharedObjects;
use Helper\SpiesOnExceptions;
use RJozwiak\Libroteca\Application\Command\{
    LendBookCopy, LendBookCopyHandler, ProlongBookLoan, ProlongBookLoanHandler, ReturnBookCopy, ReturnBookCopyHandler
};
use RJozwiak\Libroteca\Application\CommandBus;
use RJozwiak\Libroteca\Domain\Model\Book\BookRepository;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBNFactory;
use RJozwiak\Libroteca\Domain\Model\BookCopy\{
    BookCopyID, BookCopyRepository
};
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoan;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanFactory;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanRepository;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookCopyAlreadyBorrowedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAlreadyProlongedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAttemptWhenHavingOverdueLoanException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanNotFoundException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\EndingOverdueLoanWithoutRemarksException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\MaxOngoingLoansExceededException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\ProlongOverdueBookLoanException;
use RJozwiak\Libroteca\Domain\Model\Reader\{
    Email, ReaderID, ReaderRepository
};
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver\{
    CommandHandlerResolver, Inflector\ClassNameInflector, Locator\InMemoryHandlerLocator
};
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\SimpleCommandBus;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Book\InMemoryBookRepository;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\BookCopy\InMemoryBookCopyRepository;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\BookLoan\InMemoryBookLoanRepository;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\InMemoryReaderRepository;
use Webmozart\Assert\Assert;

class LoanContext implements Context, SnippetAcceptingContext
{
    use SpiesOnExceptions, ClearsBetweenScenarios;

    /** @var CommandBus */
    private $commandBus;

    /** @var ReaderRepository */
    private $readerRepository;
    /** @var BookRepository */
    private $bookRepository;
    /** @var BookCopyRepository */
    private $bookCopyRepository;
    /** @var BookLoanRepository */
    private $bookLoanRepository;
    /** @var ISBNFactory */
    private $isbnFactory;
    /** @var BookLoanFactory */
    private $bookLoanFactory;

    /** @var BookLoanID */
    private $currentBookLoanID;
    /** @var ReaderID */
    private $currentReaderID;
    /** @var BookCopyID */
    private $currentBookCopyID;

    /** @var \DateTimeImmutable */
    private $now;
    /** @var \DateTimeImmutable */
    private $today;

    public function __construct()
    {
        $this->readerRepository = SharedObjects::loadOrCreate(InMemoryReaderRepository::class);
        $this->bookRepository = SharedObjects::loadOrCreate(InMemoryBookRepository::class);
        $this->bookCopyRepository = SharedObjects::loadOrCreate(InMemoryBookCopyRepository::class);
        $this->bookLoanRepository = SharedObjects::loadOrCreate(InMemoryBookLoanRepository::class);
        $this->isbnFactory = new ISBNFactory();
        $this->bookLoanFactory = new BookLoanFactory($this->bookLoanRepository);
        $this->commandBus = new SimpleCommandBus(
            new CommandHandlerResolver(
                new ClassNameInflector(),
                new InMemoryHandlerLocator([
                    new LendBookCopyHandler(
                        $this->readerRepository,
                        $this->bookCopyRepository,
                        $this->bookLoanRepository,
                        $this->bookLoanFactory
                    ),
                    new ProlongBookLoanHandler(
                        $this->bookLoanRepository
                    ),
                    new ReturnBookCopyHandler(
                        $this->bookLoanRepository
                    )
                ])
            )
        );

        $this->now = new \DateTimeImmutable();
        $this->today = $this->now->setTime(0, 0, 0);
    }

    /**
     * @Given there is a loan for a book copy with ISBN :isbn to reader with email :email till :till
     */
    public function createBookLoanTill(string $isbn, string $email, string $till)
    {
        // handle "day after tomorrow"
        $till = $till === 'day after tomorrow' ? 'tomorrow + 1 day' : $till;

        $dueDate = $this->today->modify($till);
        $today = $this->today;

        // handle creation of "invalid" book loans (before today)
        if ($dueDate < $today) {
            $today = $dueDate;
        }

        $this->createBookLoanFromISBN($isbn, $email, $dueDate, $today);
    }

    /**
     * @When I lend a book copy with ISBN :isbn to the reader with email :email for :days days
     */
    public function createBookLoanForDays(string $isbn, string $email, int $days)
    {
        $this->spyOnException(
            [$this, 'createBookLoanFromISBN'],
            [$isbn, $email, $this->today->modify("+ $days days"), $this->today]
        );
    }

    /**
     * @When I lend books copies to reader with email :email:
     */
    public function createBooksLoansForReaderWithEmail(string $email, TableNode $booksLoans)
    {
        $this->currentReaderID = $this->readerRepository->findOneByEmail(new Email($email))->id();

        $this->spyOnException(function () use ($email, $booksLoans) {
            foreach ($booksLoans as $bookLoan) {
                $this->createBookLoanFromISBN(
                    $bookLoan['isbn'],
                    $email,
                    $this->today->modify("+ {$bookLoan['for N days']} days"),
                    $this->today
                );
            }
        });
    }

    /**
     * @param string $bookISBN
     * @param string $readerEmail
     * @param \DateTimeImmutable $dueTo
     * @param \DateTimeImmutable $today
     * @throws \InvalidArgumentException
     */
    private function createBookLoanFromISBN(
        string $bookISBN,
        string $readerEmail,
        \DateTimeImmutable $dueTo,
        \DateTimeImmutable $today
    ) {
        $isbn = $this->isbnFactory->create($bookISBN);
        $email = new Email($readerEmail);

        $this->currentReaderID = $this->readerRepository->findOneByEmail($email)->id();
        $book = $this->bookRepository->findOneByISBN($isbn);
        $bookCopies = $this->bookCopyRepository->findByBookID($book->id());
        Assert::greaterThan(count($bookCopies), 0);
        $this->currentBookCopyID = $bookCopies[0]->id();
        $this->currentBookLoanID = $this->bookLoanRepository->nextID();

        $this->commandBus->handle(
            new LendBookCopy(
                $this->currentBookLoanID->id(),
                $this->currentReaderID->id(),
                $this->currentBookCopyID->id(),
                $dueTo,
                $today
            )
        );
    }

    /**
     * @When I lend the same book copy to the reader with email :email for :days days
     */
    public function createAnotherBookLoanForSameCopy(string $email, int $days)
    {
        $this->spyOnException(function () use ($email, $days) {
            $this->currentBookLoanID = $this->bookLoanRepository->nextID();
            $this->currentReaderID = $this->readerRepository->findOneByEmail(new Email($email))->id();

            $this->commandBus->handle(
                new LendBookCopy(
                    $this->currentBookLoanID->id(),
                    $this->currentReaderID->id(),
                    $this->currentBookCopyID->id(),
                    $this->today->modify("+ $days days"),
                    $this->today
                )
            );
        });
    }

    /**
     * @Then there should be book loan for a reader with email :email for :days days
     */
    public function assertBookLoanExists(string $email, int $days)
    {
        $reader = $this->readerRepository->findOneByEmail(new Email($email));
        $bookLoan = $this->bookLoanRepository->findOngoingByReaderID($reader->id());

        Assert::count($bookLoan, 1);
        Assert::allIsInstanceOf($bookLoan, BookLoan::class);
        Assert::eq(
            $bookLoan[0]->dueDate(),
            $this->today->modify("+ $days days")
        );
    }

    /**
     * @Then /^there should be (\d+) book cop(?:y|ies) with ISBN "([^"]*)" available for loan$/
     * @Then there should be no book copies with ISBN :isbn available for loan
     */
    public function assertSomeNumberOfBookCopiesAvailableForLoan(int $copies = 0, string $isbn)
    {
        $book = $this->bookRepository->findOneByISBN($this->isbnFactory->create($isbn));
        $bookCopies = $this->bookCopyRepository->findByBookID($book->id());
        $availableCopies = count($bookCopies);

        foreach ($bookCopies as $bookCopy) {
            $bookLoan = $this->bookLoanRepository->findOngoingByBookCopyID($bookCopy->id());

            if ($bookLoan) {
                $availableCopies--;
            }
        }

        Assert::eq($copies, $availableCopies);
    }

    /**
     * @Then I should be notified that book copy is already lent
     */
    public function assertBookCopyAlreadyLentExceptionHasBeenThrown()
    {
        Assert::isInstanceOf($this->catchedException, BookCopyAlreadyBorrowedException::class);
        Assert::eq(
            "Book copy (id: {$this->currentBookCopyID}) is already borrowed.",
            $this->catchedException->getMessage()
        );
    }

    /**
     * @Then the loan attempt should not be successful
     */
    public function assertBookLoanDoesNotExist()
    {
        Assert::throws(
            function () { $this->bookLoanRepository->get($this->currentBookLoanID); },
            BookLoanNotFoundException::class
        );
    }

    /**
     * @Then I should be notified that reader must return books first to proceed with loan attempt
     */
    public function assertBookLoanAttemptWhenHavingOverdueLoanExceptionHasBeenThrown()
    {
        Assert::isInstanceOf($this->catchedException, BookLoanAttemptWhenHavingOverdueLoanException::class);
        Assert::eq(
            "Could not start book loan. Reader (id: {$this->currentReaderID}) has ongoing overdue loan.",
            $this->catchedException->getMessage()
        );
    }

    /**
     * @Then I should be notified that loan period can last at most for :days days
     */
    public function assertBookLoanPeriodOutOfLimitExceptionHasBeenThrown(int $days) // TODO: pass info. in exception
    {
        Assert::isInstanceOf($this->catchedException, \InvalidArgumentException::class);
        Assert::eq('Exceeded max. loan period.', $this->catchedException->getMessage());
    }

    /**
     * @Then I should be notified that reader has reached limit of borrowed books
     */
    public function assertNotExceededMaxOngoingLoansForReader()
    {
        Assert::isInstanceOf($this->catchedException, MaxOngoingLoansExceededException::class);
        Assert::eq(
            "Max ongoing loans exceeded for reader with id `{$this->currentReaderID}`.",
            $this->catchedException->getMessage()
        );
    }

    /**
     * @Then loan attempt for book copy with ISBN :isbn should not be successful
     */
    public function loanAttemptForBookCopyWithIsbnShouldNotBeSuccessful(string $isbn)
    {
        $bookLoans = $this->bookLoanRepository->findOngoingByReaderID($this->currentReaderID);

        foreach ($bookLoans as $bookLoan) {
            $bookCopy = $this->bookCopyRepository->get($bookLoan->bookCopyID());
            $book = $this->bookRepository->get($bookCopy->bookID());
            Assert::notEq($book->isbn()->isbn(), $isbn);
        }

        Assert::null($this->bookLoanRepository->findOngoingByBookCopyID($this->currentBookCopyID));
    }

    /**
     * @Given this loan has already been prolonged for :days days
     * @When I prolong loan period on this book copy by :days days
     */
    public function prolongBookLoanByDays(int $days)
    {
        $this->spyOnException(function () use ($days) {
            $this->commandBus->handle(
                new ProlongBookLoan(
                    $this->currentBookLoanID->id(),
                    $this->today->modify("+ $days days"),
                    $this->today
                )
            );
        });
    }

    /**
     * @Then this loan should be prolonged by :days days
     */
    public function assertLoanProlongedByDays(int $days)
    {
        $bookLoan = $this->bookLoanRepository->get($this->currentBookLoanID);

        Assert::true($bookLoan->isProlonged());
        Assert::eq($bookLoan->dueDate(), $this->today->modify("+ {$days} days"));
    }

    /**
     * @Then this loan should expire :days days later
     */
    public function assertBookLoanExpiration(int $days)
    {
        $dayBeforeExpiration = $days - 1;
        $bookLoan = $this->bookLoanRepository->get($this->currentBookLoanID);

        Assert::false($bookLoan->isOverdue($this->today->modify("+ {$dayBeforeExpiration} days")));
        Assert::true($bookLoan->isOverdue($this->today->modify("+ {$days} days")));
    }

    /**
     * @Then this loan period should not be prolonged
     */
    public function assertLoanNotProlonged()
    {
        $bookLoan = $this->bookLoanRepository->get($this->currentBookLoanID);

        Assert::false($bookLoan->isProlonged());
    }

    /**
     * @Then I should be notified that the loan period has been already prolonged
     */
    public function assertBookLoanAlreadyProlongedExceptionHasBeenThrown()
    {
        Assert::isInstanceOf($this->catchedException, BookLoanAlreadyProlongedException::class);
    }

    /**
     * @Then I should be notified that the loan period has already expired
     */
    public function assertCannotProlongPeriodOfOverdueBookLoan()
    {
        Assert::isInstanceOf($this->catchedException, ProlongOverdueBookLoanException::class);
    }

    /**
     * @Then I should be notified that the loan prolongation is at most :days days
     */
    public function assertLoanProlongationWithinRange(int $days) // TODO: pass info. in exception
    {
        Assert::isInstanceOf($this->catchedException, \InvalidArgumentException::class);
        Assert::eq("Exceeded max. prolongation period.", $this->catchedException->getMessage());
    }

    /**
     * @When I accept this book copy without remarks
     * @When I accept this book copy with remarks:
     */
    public function returnBookCopyOfReader(PyStringNode $remarks = null)
    {
        $this->spyOnException(function () use ($remarks) {
            $this->commandBus->handle(
                new ReturnBookCopy(
                    $this->currentBookLoanID->id(),
                    $this->today,
                    $remarks ? $remarks->getRaw() : null
                )
            );
        });
    }

    /**
     * @Then the book loan should has ended
     */
    public function assertBookLoanEnded()
    {
        $bookLoan = $this->bookLoanRepository->get($this->currentBookLoanID);

        Assert::true($bookLoan->hasEnded());
    }

    /**
     * @Then the book loan should has not ended
     */
    public function assertBookLoanNotEnded()
    {
        $bookLoan = $this->bookLoanRepository->get($this->currentBookLoanID);

        Assert::false($bookLoan->hasEnded());
    }

    /**
     * @Then I should be notified that adding remarks to overdue loan is required
     */
    public function iShouldBeNotifiedThatAddingRemarksToOverdueLoanIsRequired()
    {
        Assert::isInstanceOf($this->catchedException, EndingOverdueLoanWithoutRemarksException::class);
        Assert::eq('Ending overdue loan must have remarks.', $this->catchedException->getMessage());
    }

    /**
     * @Then the reader should be notified on email :email about loan expiration with message:
     */
    public function theReaderShouldBeNotifiedOnEmailAboutLoanExpirationWithMessage(
        $email,
        PyStringNode $message
    ) {
        throw new PendingException(); // TODO:
    }
}
