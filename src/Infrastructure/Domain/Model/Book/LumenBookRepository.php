<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Book;

use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Infrastructure\Persistence\Lumen;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBNFactory;
use RJozwiak\Libroteca\Domain\Model\Book\Author;
use RJozwiak\Libroteca\Domain\Model\Book\Book;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\Book\BookRepository;
use RJozwiak\Libroteca\Domain\Model\Book\Exception\BookNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN;
use RJozwiak\Libroteca\Domain\Model\Book\Title;

class LumenBookRepository implements BookRepository
{
    /** @var ISBNFactory */
    private $isbnFactory;

    /**
     * @param ISBNFactory $isbnFactory
     */
    public function __construct(ISBNFactory $isbnFactory)
    {
        $this->isbnFactory = $isbnFactory;
    }

    /**
     * @return BookID
     */
    public function nextID(): BookID
    {
        return new BookID(Uuid::uuid4()->toString());
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return Lumen\Model\Book::count();
    }

    /**
     * @param Book $book
     */
    public function save(Book $book)
    {
        $attributes = ['id' => $book->id()->id()];
        $data = [
            'isbn' => $book->isbn()->isbn(),
            'title' => $book->title()->title(),
            'authors' => array_map(
                function (Author $author) { return $author->name(); },
                $book->authors()
            )
        ];

        Lumen\Model\Book::updateOrCreate($attributes, $data);
    }

    /**
     * @param BookID $id
     * @return Book
     * @throws BookNotFoundException
     */
    public function get(BookID $id): Book
    {
        $data = Lumen\Model\Book::find($id->id());

        if ($data === null) {
            throw new BookNotFoundException();
        }

        return $this->createBook($data['id'], $data['isbn'], $data['title'], $data['authors']);
    }

    /**
     * @param ISBN $isbn
     * @return null|Book
     */
    public function findOneByISBN(ISBN $isbn): ?Book
    {
        $data = Lumen\Model\Book::where('isbn', $isbn->isbn())->first();

        if ($data === null) {
            return null;
        }

        return $this->createBook($data['id'], $data['isbn'], $data['title'], $data['authors']);
    }

    /**
     * @param Author $author
     * @param Title $title
     * @return Book[]
     */
    public function findByAuthorAndTitle(Author $author, Title $title): array
    {
        $data = Lumen\Model\Book::where([
            'author' => $author->name(),
            'title' => $title->title()
        ])->get();

        $books = [];
        foreach ($data as $row) {
            $books[] = $this->createBook($row['id'], $row['isbn'], $row['title'], $row['authors']);
        }

        return $books;
    }

    /**
     * @param int|string $id
     * @param null|string $isbn
     * @param string $title
     * @param array $authors
     * @return Book
     */
    private function createBook($id, ?string $isbn, string $title, array $authors): Book
    {
        return new Book(
            new BookID($id),
            $this->isbnFactory->create($isbn),
            $authors = array_map(
                function (string $author) {return new Author($author); },
                $authors
            ),
            new Title($title)
        );
    }
}
