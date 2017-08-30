<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\AggregateRoot;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN;

class Book extends AggregateRoot
{
    /** @var BookID */
    private $id;

    /** @var ISBN */
    private $isbn;

    /** @var Author[] */
    private $authors;

    /** @var Title */
    private $title;

//    /** @var string */
//    private $publisher;
//
//    /** @var \DateTimeImmutable */
//    private $publicationDate;

    /**
     * @param BookID $id
     * @param ISBN $isbn
     * @param Author[] $authors
     * @param Title $title
     * @throws \InvalidArgumentException
     */
    public function __construct(BookID $id, ISBN $isbn, array $authors, Title $title)
    {
        $this->id = $id;
        $this->setData($isbn, $authors, $title);
    }

    /**
     * @param ISBN $isbn
     * @param array $authors
     * @param Title $title
     * @throws \InvalidArgumentException
     */
    public function setData(ISBN $isbn, array $authors, Title $title)
    {
        $this->isbn = $isbn;
        $this->title = $title;

        $this->assertAuthorsNotEmpty($authors);

        $this->clearAuthors();
        foreach ($authors as $author) {
            $this->addAuthor($author);
        }
    }

    /**
     * @param array $authors
     * @throws \InvalidArgumentException
     */
    private function assertAuthorsNotEmpty(array $authors)
    {
        if (empty($authors)) {
            throw new \InvalidArgumentException('No authors provided.');
        }
    }

    private function clearAuthors()
    {
        $this->authors = [];
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
    public function id(): BookID
    {
        return $this->id;
    }

    /**
     * @return Title
     */
    public function title(): Title
    {
        return $this->title;
    }

    /**
     * @return Author[]
     */
    public function authors(): array
    {
        return $this->authors;
    }

    /**
     * @return ISBN
     */
    public function isbn(): ISBN
    {
        return $this->isbn;
    }
}
