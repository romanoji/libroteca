<?php

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\Book\Book;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\Book\BookRepository;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN;

class InMemoryBookRepository implements BookRepository
{
    /** @var int */
    private $nextID = 1;

    /** @var Book[] */
    private $books = [];

    /**
     * @return BookID
     */
    public function nextID()
    {
        return new BookID($this->nextID++);
    }

    /**
     * @param Book $book
     */
    public function add(Book $book)
    {
        $this->books[$book->id()->id()] = $book;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->books);
    }

    /**
     * @param BookID $id
     * @return null|Book
     */
    public function find(BookID $id)
    {
        if (!isset($this->books[$id->id()])) {
            return null;
        }

        return $this->books[$id->id()];
    }

    /**
     * @param ISBN $isbn
     * @return null|Book
     */
    public function findOneByISBN(ISBN $isbn)
    {
        foreach ($this->books as $book) {
            if ($book->isbn()->equals($isbn)) {
                return $book;
            }
        }

        return null;
    }
}
