<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Domain\Model\Book\Author;
use RJozwiak\Libroteca\Domain\Model\Book\Book;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\Book\BookRepository;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\Exception\ISBNAlreadyInUseException;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBNFactory;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\NullISBN;
use RJozwiak\Libroteca\Domain\Model\Book\Title;

class UpdateBookHandler implements CommandHandler
{
    /** @var ISBNFactory */
    private $isbnFactory;

    /** @var BookRepository */
    private $bookRepository;

    /**
     * RegisterBookHandler constructor.
     * @param ISBNFactory $isbnFactory
     * @param BookRepository $bookRepository
     */
    public function __construct(
        ISBNFactory $isbnFactory,
        BookRepository $bookRepository
    ) {
        $this->isbnFactory = $isbnFactory;
        $this->bookRepository = $bookRepository;
    }

    public function execute(UpdateBook $command) : void
    {
        $bookID = new BookID($command->bookID);
        $isbn = $this->isbnFactory->create($command->isbn);
        $authors = array_map(
            function ($author) {
                return new Author($author);
            },
            $command->authors
        );
        $title = new Title($command->title);

        $this->assertUniqueISBN($isbn);

        $book = $this->bookRepository->find($bookID);
        $book->setData($isbn, $authors, $title);
    }

    /**
     * @param ISBN $isbn
     * @throws ISBNAlreadyInUseException
     */
    private function assertUniqueISBN(ISBN $isbn)
    {
        if (!$isbn instanceof NullISBN) {
            $book = $this->bookRepository->findOneByISBN($isbn);

            if ($book instanceof Book) {
                throw new ISBNAlreadyInUseException('ISBN is already in use.');
            }
        }
    }
}
