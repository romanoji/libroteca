<?php

namespace Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use RJozwiak\Libroteca\Domain\Model\Book\Book;
use RJozwiak\Libroteca\Domain\Model\Book\BookRepository;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBNFactory;
use RJozwiak\Libroteca\Domain\Model\Book\RegisterBook;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Book\InMemoryBookRepository;
use Webmozart\Assert\Assert;

class BookContext implements Context, SnippetAcceptingContext
{
    /** @var BookRepository */
    private $bookRepository;
    /** @var ISBNFactory */
    private $isbnFactory;
    /** @var RegisterBook */
    private $registerBook;

    /** @var Book */
    private $currentBook;

    public function __construct()
    {
        $this->bookRepository = new InMemoryBookRepository();
        $this->isbnFactory = new ISBNFactory();
        $this->registerBook = new RegisterBook(
            $this->bookRepository,
            $this->isbnFactory
        );
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
     * @When I register a book of :authors titled :title
     * @When I register a book of :authors titled :title with ISBN :isbn
     */
    public function iRegisterABookOfTitledWithISBN($authors, $title, $isbn = null)
    {
        $this->currentBook = $this->registerBook->execute($authors, $title, $isbn);
    }

    /**
     * @Transform :authors
     */ // /^(\w+((, ?\w+)+)?)$/
    public function castStringToArray($authors)
    {
        return array_map('trim', explode(',', $authors));
    }

    /**
     * @Given there (is|are) :copies book cop(y|ies) of ISBN :isbn available for loan
     */
    public function thereAreBookCopiesOfISBNAvailableForLoan($copies, $isbn)
    {
        throw new PendingException();
    }

    /**
     * @Given there are registered a books copies:
     */
    public function thereAreRegisteredABooksCopies(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @When I register copy of a book with an ISBN :isbn
     */
    public function iRegisterCopyOfABookWithAnIsbn($isbn)
    {
        throw new PendingException();
    }

    /**
     * @Then the book should be registered in library
     */
    public function theBookShouldBeRegisteredInLibrary()
    {
        Assert::same(
            $this->bookRepository->find($this->currentBook->id()),
            $this->currentBook
        );
    }

    /**
     * @Then there should be :copies book cop(y|ies) of ISBN :isbn available for loan
     */
    public function thereShouldBeBookCopiesOfIsbnAvailableForLoan($copies, $isbn)
    {
        throw new PendingException();
    }

    /**
     * @Then there should be :copies book copy of :author titled :title available for loan
     */
    public function thereShouldBeBookCopiesOfTitledAvailableForLoan($copies, $author, $title)
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that book with provided ISBN is already registered in library
     */
    public function iShouldBeNotifiedThatBookWithProvidedIsbnIsAlreadyRegisteredInLibrary()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that ISBN is incomplete
     */
    public function iShouldBeNotifiedThatIsbnIsIncomplete()
    {
        throw new PendingException();
    }

    /**
     * @Then the book should not be registered in library
     */
    public function theBookShouldNotBeRegisteredInLibrary()
    {
        throw new PendingException();
    }

    /**
     * @Then there are no copies of this book in library
     */
    public function thereAreNoCopiesOfThisBookInLibrary()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that ISBN is invalid
     */
    public function iShouldBeNotifiedThatIsbnIsInvalid()
    {
        throw new PendingException();
    }
}
