<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\BookCopy;

use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopy;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyRepository;

class InMemoryBookCopyRepository implements BookCopyRepository
{
    /** @var int */
    private $nextID = 1;

    /** @var BookCopy[] */
    private $booksCopies = [];

    /**
     * @return BookCopyID
     */
    public function nextID()
    {
        return new BookCopyID($this->nextID++);
    }

    /**
     * @param BookCopy $bookCopy
     */
    public function add(BookCopy $bookCopy)
    {
        $this->booksCopies[$bookCopy->id()->id()] = $bookCopy;
    }

    /**
     * @param BookCopyID $id
     * @return null|BookCopy
     */
    public function find(BookCopyID $id) : ?BookCopy
    {
        if (!isset($this->booksCopies[$id->id()])) {
            return null;
        }

        return $this->booksCopies[$id->id()];
    }

    /**
     * @param BookID $bookID
     * @return BookCopy[]
     */
    public function findByBookID(BookID $bookID) : array
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
