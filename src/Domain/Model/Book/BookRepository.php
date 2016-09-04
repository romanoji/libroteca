<?php

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
    public function count();

    /**
     * @param BookID $id
     * @return Book
     */
    public function find(BookID $id);

    /**
     * @param ISBN $isbn
     * @return Book
     */
    public function findOneByISBN(ISBN $isbn);

    /**
     * @param Author $author
     * @param Title $title
     * @return Book[]
     */
    public function findByAuthorAndTitle(Author $author, Title $title);
}
