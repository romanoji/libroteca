<?php

namespace Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use Helper\SpiesOnExceptions;
use RJozwiak\Libroteca\Application\Command\RegisterBook;
use RJozwiak\Libroteca\Application\Command\RegisterBookCopy;
use RJozwiak\Libroteca\Application\Command\RegisterBookCopyHandler;
use RJozwiak\Libroteca\Application\Command\RegisterBookHandler;
use RJozwiak\Libroteca\Application\CommandBus;
use RJozwiak\Libroteca\Domain\Model\Book\Author;
use RJozwiak\Libroteca\Domain\Model\Book\Book;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\Book\BookRepository;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\Exception\ISBNAlreadyInUseException;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBNFactory;
use RJozwiak\Libroteca\Domain\Model\Book\Title;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopy;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyRepository;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\CommandHandlerResolver;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Inflector\ClassNameInflector;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Locator\InMemoryHandlerLocator;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\SimpleCommandBus;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Book\InMemoryBookRepository;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\BookCopy\InMemoryBookCopyRepository;
use Webmozart\Assert\Assert;

class BookContext implements Context, SnippetAcceptingContext
{
    use SpiesOnExceptions;

    /** @var CommandBus */
    private $commandBus;

    /** @var ISBNFactory */
    private $isbnFactory;
    /** @var BookRepository */
    private $bookRepository;
    /** @var BookCopyRepository */
    private $bookCopyRepository;

    /** @var null|BookID */
    private $currentBookID;

    public function __construct()
    {
        $this->isbnFactory = new ISBNFactory();
        $this->bookRepository = new InMemoryBookRepository();
        $this->bookCopyRepository = new InMemoryBookCopyRepository();
        $this->commandBus = new SimpleCommandBus(
            new CommandHandlerResolver(
                new ClassNameInflector(),
                new InMemoryHandlerLocator([
                    new RegisterBookHandler($this->isbnFactory, $this->bookRepository),
                    new RegisterBookCopyHandler($this->bookCopyRepository)
                ])
            )
        );
    }

    /**
     * @Transform :authors
     */ // /^(\w+((, ?\w+)+)?)$/
    public function castStringToArray($authors)
    {
        return array_map('trim', explode(',', $authors));
    }

    /**
     * @Given there was no book previously registered with ISBN :isbn in library
     */
    public function thereWasNoBookPreviouslyRegisteredWithIsbnInLibrary($isbn)
    {
        $book = $this->bookRepository->findOneByISBN(
            $this->isbnFactory->create($isbn)
        );

        Assert::null($book);
    }

    /**
     * @Given there is registered a book of :authors titled :title with ISBN :isbn
     */
    public function thereIsRegisteredABookOfTitledWithISBN($authors, $title, $isbn)
    {
        $this->currentBookID = $this->bookRepository->nextID();
        $this->commandBus->handle(
            new RegisterBook($this->currentBookID->id(), $isbn, $authors, $title)
        );
    }

    /**
     * @Given there is :copies book copy of ISBN :isbn available for loan
     * @Given there are :copies book copies of ISBN :isbn available for loan
     */
    public function thereAreBookCopiesOfISBNAvailableForLoan($copies, $isbn)
    {
        $this->currentBookID = $this->bookRepository
            ->findOneByISBN($this->isbnFactory->create($isbn))
            ->id();

        for ($i = 0; $i < $copies; $i++) {
            $bookCopyID = $this->bookCopyRepository->nextID();
            $this->commandBus->handle(
                new RegisterBookCopy($bookCopyID->id(), $this->currentBookID->id())
            );
        }
    }

    /**
     * @Given there are registered a books copies:
     */
    public function thereAreRegisteredABooksCopies(TableNode $books)
    {
        foreach ($books as $book) {
            $authors = explode(',', $book['authors']);

            $this->currentBookID = $this->bookRepository->nextID();
            $this->commandBus->handle(
                new RegisterBook(
                    $this->currentBookID->id(),
                    $book['isbn'],
                    $authors,
                    $book['title']
                )
            );

            for ($i = 0; $i < $book['copies']; $i++) {
                $bookCopyID = $this->bookCopyRepository->nextID();
                $this->commandBus->handle(
                    new RegisterBookCopy($bookCopyID->id(), $this->currentBookID->id())
                );
            }
        }
    }

    /**
     * @When I register a book copy of :authors titled :title
     * @When I register a book copy of :authors titled :title with ISBN :isbn
     */
    public function iRegisterABookCopyOfTitledWithISBN($authors, $title, $isbn = null)
    {
        $this->spyOnException(function () use ($authors, $title, $isbn) {
            $this->currentBookID = $this->bookRepository->nextID();
            $this->commandBus->handle(
                new RegisterBook(
                    $this->currentBookID->id(),
                    $isbn,
                    $authors,
                    $title
                )
            );

            // TODO: include adding a copy in RegisterBook command
            $bookCopyID = $this->bookCopyRepository->nextID();
            $this->commandBus->handle(
                new RegisterBookCopy($bookCopyID->id(), $this->currentBookID->id())
            );
        });
    }

    /**
     * @When I register book copy by ISBN :isbn
     */
    public function iRegisterBookCopyByIsbn($isbn)
    {
        $this->spyOnException(function () use ($isbn) {
            $this->currentBookID = $this->bookRepository
                ->findOneByISBN($this->isbnFactory->create($isbn))
                ->id();

            $bookCopyID = $this->bookCopyRepository->nextID();
            $this->commandBus->handle(
                new RegisterBookCopy($bookCopyID->id(), $this->currentBookID->id())
            );
        });
    }

    /**
     * @Then the book should be registered in library
     */
    public function theBookShouldBeRegisteredInLibrary()
    {
        Assert::isInstanceOf(
            $this->bookRepository->find($this->currentBookID),
            Book::class
        );
    }

    /**
     * @Then the book should not be registered in library
     */
    public function theBookShouldNotBeRegisteredInLibrary()
    {
        Assert::eq(0, $this->bookRepository->count());
    }

    /**
     * @Then /^there should be (\d+) book cop(?:y|ies) of ISBN "([^"]*)" available for loan$/
     */
    public function thereShouldBeBookCopiesOfIsbnAvailableForLoan($copies = 0, $isbn)
    {
        $book = $this->bookRepository->findOneByISBN(
            $this->isbnFactory->create($isbn)
        );
        $bookCopies = $this->bookCopyRepository->findByBookID($book->id());
        $availableCopies = 0;

        foreach ($bookCopies as $bookCopy) {
            if (!$bookCopy->isLent()) {
                $availableCopies++;
            }
        }

        Assert::eq($copies, $availableCopies);
        // TODO:
    }

    /**
     * @Then /^there should be (\d+) book cop(?:y|ies) of "([^"]*)" titled "([^"]*)" available for loan$/
     */
    public function thereShouldBeBookCopiesOfTitledAvailableForLoan($copies, $author, $title)
    {
        $book = $this->bookRepository->findByAuthorAndTitle(
            new Author($author),
            new Title($title)
        )[0];
        $bookCopies = $this->bookCopyRepository->findByBookID($book->id());
        $availableCopies = 0;

        foreach ($bookCopies as $bookCopy) {
            if (!$bookCopy->isLent()) {
                $availableCopies++;
            }
        }

        Assert::eq($copies, $availableCopies);
        // TODO:
    }

    /**
     * @Then I should be notified that book with provided ISBN is already registered in library
     */
    public function iShouldBeNotifiedThatBookWithProvidedIsbnIsAlreadyRegisteredInLibrary()
    {
        Assert::isInstanceOf($this->catchedException, ISBNAlreadyInUseException::class);
        Assert::eq('ISBN is already in use.', $this->catchedException->getMessage());
    }

    /**
     * @Then I should be notified that ISBN has invalid length
     */
    public function iShouldBeNotifiedThatIsbnHasInvalidLength()
    {
        Assert::isInstanceOf($this->catchedException, \InvalidArgumentException::class);
        Assert::eq('Invalid isbn length.', $this->catchedException->getMessage());
    }

    /**
     * @Then I should be notified that ISBN has invalid format
     */
    public function iShouldBeNotifiedThatIsbnHasInvalidFormat()
    {
        Assert::isInstanceOf($this->catchedException, \InvalidArgumentException::class);
        Assert::eq('Invalid isbn format.', $this->catchedException->getMessage());
    }
}
