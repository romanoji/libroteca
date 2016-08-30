<?php

namespace RJozwiak\Libroteca\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBNFactory;

class RegisterBook
{
    /** @var BookRepository */
    private $bookRepository;
    /** @var ISBNFactory */
    private $isbnFactory;
    
    /**
     * RegisterBook constructor.
     * @param BookRepository $bookRepository
     * @param ISBNFactory $isbnFactory
     */
    public function __construct(
        BookRepository $bookRepository,
        ISBNFactory $isbnFactory
    ) {
        $this->bookRepository = $bookRepository;
        $this->isbnFactory = $isbnFactory;
    }

    /**
     * @param array $authors
     * @param string $title
     * @param null|string $isbn
     * @return Book
     */
    public function execute(array $authors, $title, $isbn = null)
    {
        $bookAuthors = array_map(
            function ($author) {
                new Author($author);
            },
            $authors
        );

        $book = new Book(
            $this->bookRepository->nextID(),
            $this->isbnFactory->create($isbn),
            $bookAuthors,
            new Title($title)
        );

        $this->bookRepository->add($book);

        // TODO: event

        return $book;
    }
}
