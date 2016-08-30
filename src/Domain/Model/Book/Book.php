<?php

namespace RJozwiak\Libroteca\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN;

class Book
{
    /** @var BookID */
    private $id;

    /** @var ISBN */
    private $isbn;

    /** @var Author[] */
    private $authors;

    /** @var Title */
    private $title;

    /**
     * Book constructor.
     * @param BookID $id
     * @param ISBN $isbn
     * @param Author[] $authors
     * @param Title $title
     */
    public function __construct(BookID $id, ISBN $isbn, $authors, Title $title)
    {
        $this->setID($id);
        $this->setISBN($isbn);
        $this->setAuthors($authors);
        $this->setTitle($title);
    }

    /**
     * @param BookID $id
     */
    private function setID(BookID $id)
    {
        $this->id = $id;
    }

    /**
     * @param ISBN $isbn
     */
    private function setISBN(ISBN $isbn)
    {
        $this->isbn = $isbn;
    }

    /**
     * @param Author[] $authors
     */
    private function setAuthors($authors)
    {
        // TODO: foreach instanceof Author
        $this->authors = $authors;
    }

    /**
     * @param Title $title
     */
    private function setTitle(Title $title)
    {
        $this->title = $title;
    }

    /**
     * @return BookID
     */
    public function id()
    {
        return $this->id;
    }
}
