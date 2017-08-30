<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\Book\{
    Author, Book, BookID, BookRepository, Exception\BookNotFoundException, ISBN\ISBN, Title
};

class InMemoryBookRepository implements BookRepository
{
    /** @var int */
    private $nextID = 1;

    /** @var Book[] */
    private $books = [];

    /**
     * @return BookID
     */
    public function nextID(): BookID
    {
        return new BookID($this->nextID++);
    }

    /**
     * @param Book $book
     */
    public function save(Book $book)
    {
        $this->books[$book->id()->id()] = $book;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->books);
    }

    /**
     * @param BookID $id
     * @return Book
     * @throws BookNotFoundException
     */
    public function get(BookID $id): Book
    {
        if (!isset($this->books[$id->id()])) {
            throw new BookNotFoundException();
        }

        return $this->books[$id->id()];
    }

    /**
     * @param ISBN $isbn
     * @return null|Book
     */
    public function findOneByISBN(ISBN $isbn): ?Book
    {
        foreach ($this->books as $book) {
            if ($book->isbn()->equals($isbn)) {
                return $book;
            }
        }

        return null;
    }

    /**
     * @param Author $author
     * @param Title $title
     * @return Book[]
     */
    public function findByAuthorAndTitle(Author $author, Title $title): array
    {
        $books = [];

        foreach ($this->books as $book) {
            if ($book->title()->equals($title)) {
                foreach ($book->authors() as $bookAuthor) {
                    if ($bookAuthor->equals($author)) {
                        $books[] = $book;
                        break;
                    }
                }
            }
        }

        return $books;
    }
}
