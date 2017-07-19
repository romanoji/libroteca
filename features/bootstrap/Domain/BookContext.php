<?php
declare(strict_types=1);

namespace Domain;

use Behat\Behat\Context\{
    Context, SnippetAcceptingContext
};
use Behat\Gherkin\Node\TableNode;
use Helper\SpiesOnExceptions;
use RJozwiak\Libroteca\Application\Command\{
    RegisterBook, RegisterBookCopy, RegisterBookCopyHandler, RegisterBookHandler
};
use RJozwiak\Libroteca\Application\CommandBus;
use RJozwiak\Libroteca\Domain\Model\Book\{
    Author, Book, BookID, BookRepository, ISBN\Exception\ISBNAlreadyInUseException, ISBN\ISBNFactory, Title
};
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyRepository;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\{
    CommandHandlerResolver, Inflector\ClassNameInflector, Locator\InMemoryHandlerLocator
};
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
    public function castStringToArray(string $authors)
    {
        return array_map('trim', explode(',', $authors));
    }

    /**
     * @Given there is no book with ISBN :isbn registered in library
     */
    public function assertThereIsNoBookByISBN(string $isbn)
    {
        $book = $this->bookRepository->findOneByISBN(
            $this->isbnFactory->create($isbn)
        );

        Assert::null($book);
    }

    /**
     * @Given there is registered a book :title of :authors with ISBN :isbn
     */
    public function createBookFromData(string $title, array $authors, ?string $isbn)
    {
        $this->currentBookID = $this->bookRepository->nextID();
        $this->commandBus->handle(
            new RegisterBook($this->currentBookID->id(), $isbn, $authors, $title)
        );
    }

    /**
     * @Given there is :copies book copy with ISBN :isbn available for loan
     * @Given there are :copies book copies with ISBN :isbn available for loan
     */
    public function createBookCopiesByISBN($copies, string $isbn)
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
    public function createBookCopies(TableNode $books)
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
     * @When I register a book :title of :authors
     * @When I register a book :title of :authors with ISBN :isbn
     */
    public function createBookFromDataWithException(string $title, array $authors, ?string $isbn = null)
    {
        $this->spyOnException([$this, 'createBookFromData'], [$title, $authors, $isbn]);
    }

    /**
     * @When I register book copy by ISBN :isbn
     */
    public function createBookCopyByISBNWithException(string $isbn)
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
     * @Then the book :title of :author with ISBN :isbn should be registered in library
     * @Then the book :title of :author should be registered in library
     */
    public function assertBookWithDataIsRegisteredInLibrary(string $title, string $author, ?string $isbn = null)
    {
        if ($isbn) {
            $book = $this->bookRepository->findOneByISBN($this->isbnFactory->create($isbn));
        } else {
            $books = $this->bookRepository->findByAuthorAndTitle(new Author($author), new Title($title));
            Assert::count($books, 1);
            $book = $books[0];
        }
        Assert::isInstanceOf($book, Book::class);
    }

    /**
     * @Then the book should be registered in library
     */
    public function assertBookIsRegisteredInLibrary()
    {
        Assert::isInstanceOf(
            $this->bookRepository->find($this->currentBookID),
            Book::class
        );
    }

    /**
     * @Then the book should not be registered in library
     */
    public function assertThereIsNoBookRegisteredInLibrary()
    {
        Assert::eq(0, $this->bookRepository->count());
    }

    /**
     * @Then /^there should be (\d+) book cop(?:y|ies) with ISBN "([^"]*)" available for loan$/
     */
    public function assertSomeNumberOfBookCopiesAvailableForLoan(int $copies = 0, string $isbn)
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
    }

    /**
     * @Then I should be notified that book with provided ISBN is already registered in library
     */
    public function assertISBNAlreadyInUseExceptionHasBeenThrown()
    {
        Assert::isInstanceOf($this->catchedException, ISBNAlreadyInUseException::class);
        Assert::eq('ISBN is already in use.', $this->catchedException->getMessage());
    }

    /**
     * @Then I should be notified that ISBN has invalid length
     */
    public function assertInvalidISBNLengthExceptinHasBeenThrown()
    {
        Assert::isInstanceOf($this->catchedException, \InvalidArgumentException::class);
        Assert::eq('Invalid isbn length.', $this->catchedException->getMessage());
    }

    /**
     * @Then I should be notified that ISBN has invalid format
     */
    public function assertInvalidISBNFormatExceptionHasBeenThrown()
    {
        Assert::isInstanceOf($this->catchedException, \InvalidArgumentException::class);
        Assert::eq('Invalid isbn format.', $this->catchedException->getMessage());
    }
}
