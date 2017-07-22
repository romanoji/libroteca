<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN;

interface BookRepository
{
    /**
     * @return BookID
     */
    public function nextID();

    /**
     * @param Book $book
     */
    public function add(Book $book);

    /**
     * @return int
     */
    public function count() : int;

    /**
     * @param BookID $id
     * @return null|Book
     */
    public function find(BookID $id) : ?Book;

    /**
     * @param ISBN $isbn
     * @return null|Book
     */
    public function findOneByISBN(ISBN $isbn) : ?Book;

    /**
     * @param Author $author
     * @param Title $title
     * @return Book[]
     */
    public function findByAuthorAndTitle(Author $author, Title $title) : array;
}
