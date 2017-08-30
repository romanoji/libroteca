<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\BookCopy;

use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\{
    BookCopy, BookCopyID, BookCopyRepository, Exception\BookCopyNotFoundException
};

class InMemoryBookCopyRepository implements BookCopyRepository
{
    /** @var int */
    private $nextID = 1;

    /** @var BookCopy[] */
    private $booksCopies = [];

    /**
     * @return BookCopyID
     */
    public function nextID(): BookCopyID
    {
        return new BookCopyID($this->nextID++);
    }

    /**
     * @param BookCopy $bookCopy
     */
    public function save(BookCopy $bookCopy)
    {
        $this->booksCopies[$bookCopy->id()->id()] = $bookCopy;
    }

    /**
     * @param BookCopyID $id
     * @return BookCopy
     * @throws BookCopyNotFoundException
     */
    public function get(BookCopyID $id): BookCopy
    {
        if (!isset($this->booksCopies[$id->id()])) {
            throw new BookCopyNotFoundException();
        }

        return $this->booksCopies[$id->id()];
    }

    /**
     * @param BookID $bookID
     * @return BookCopy[]
     */
    public function findByBookID(BookID $bookID): array
    {
        $booksCopies = [];

        foreach ($this->booksCopies as $bookCopy) {
            if ($bookCopy->bookID()->equals($bookID)) {
                $booksCopies[] = $bookCopy;
            }
        }

        return $booksCopies;
    }
}
