<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command\ImportBooks\ImportFileLoader;
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
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopy;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyRepository;

class ImportBooksHandler implements CommandHandler
{
    /** @var ISBNFactory */
    private $isbnFactory;

    /** @var BookRepository */
    private $bookRepository;

    /** @var BookCopyRepository */
    private $bookCopyRepository;

    /** @var ImportFileLoader */
    private $booksImportFileLoader;

    /**
     * @param ISBNFactory $isbnFactory
     * @param BookRepository $bookRepository
     * @param BookCopyRepository $bookCopyRepository
     * @param ImportFileLoader $booksImportFileLoader
     */
    public function __construct(
        ISBNFactory $isbnFactory,
        BookRepository $bookRepository,
        BookCopyRepository $bookCopyRepository,
        ImportFileLoader $booksImportFileLoader
    ) {
        $this->isbnFactory = $isbnFactory;
        $this->bookRepository = $bookRepository;
        $this->bookCopyRepository = $bookCopyRepository;
        $this->booksImportFileLoader = $booksImportFileLoader;
    }

    /**
     * @param ImportBooks $command
     * @throws \InvalidArgumentException
     */
    public function execute(ImportBooks $command): void
    {
        $booksData = $this->booksImportFileLoader->loadBooksData($command->file());

        foreach ($booksData as $bookData) {
            try {
                $bookID = $this->createBook($bookData->isbn(), $bookData->title(), $bookData->authors());
                $this->createBookCopies($bookID, $bookData->amount());
            } catch (\InvalidArgumentException | ISBNAlreadyInUseException $e) {
                // TODO: log errors to some result set
            }
        }

        // TODO: save results of this command or send some sort of notification (email?)
    }

    /**
     * @param null|string $isbn
     * @param string $title
     * @param array $authors
     * @return BookID
     * @throws ISBNAlreadyInUseException
     * @throws \InvalidArgumentException
     */
    private function createBook(?string $isbn, string $title, array $authors): BookID
    {
        $bookID = $this->bookRepository->nextID();
        $isbn = $this->isbnFactory->create($isbn);
        $title = new Title($title);
        $authors = array_map(
            function (string $author) {
                return new Author($author);
            },
            $authors
        );

        $this->assertUniqueISBN($isbn);

        $book = new Book($bookID, $isbn, $authors, $title);
        $this->bookRepository->save($book);

        return $bookID;
    }

    /**
     * @param BookID $bookID
     * @param int $amount
     * @return BookCopyID[]
     */
    private function createBookCopies(BookID $bookID, int $amount): array
    {
        $ids = [];

        for ($i = 0; $i < $amount; $i++) {
            $bookCopyID = $this->bookCopyRepository->nextID();
            $bookCopy = new BookCopy($bookCopyID, $bookID);

            $this->bookCopyRepository->save($bookCopy);

            $ids[] = $bookCopyID;
        }

        return $ids;
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
