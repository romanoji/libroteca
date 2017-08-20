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
     * UpdateBookHandler constructor.
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
            function (string $author) {
                return new Author($author);
            },
            $command->authors
        );
        $title = new Title($command->title);

        $this->assertUniqueISBN($isbn, $bookID);

        $book = $this->bookRepository->get($bookID);
        $book->setData($isbn, $authors, $title);

        $this->bookRepository->save($book);
    }

    /**
     * @param ISBN $isbn
     * @param BookID $bookID
     * @throws ISBNAlreadyInUseException
     */
    private function assertUniqueISBN(ISBN $isbn, BookID $bookID)
    {
        if (!$isbn instanceof NullISBN) {
            $book = $this->bookRepository->findOneByISBN($isbn);

            if ($book instanceof Book && !$book->id()->equals($bookID)) {
                throw new ISBNAlreadyInUseException('ISBN is already in use.');
            }
        }
    }
}
