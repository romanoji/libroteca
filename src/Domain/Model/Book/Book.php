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
        $this->id = $id;
        $this->isbn = $isbn;
        $this->title = $title;

        foreach ($authors as $author) {
            $this->addAuthor($author);
        }
    }

    /**
     * @param Author $author
     */
    private function addAuthor(Author $author)
    {
        $this->authors[] = $author;
    }

    /**
     * @return BookID
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return Title
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return Author[]
     */
    public function authors()
    {
        return $this->authors;
    }

    /**
     * @return ISBN
     */
    public function isbn()
    {
        return $this->isbn;
    }
}
