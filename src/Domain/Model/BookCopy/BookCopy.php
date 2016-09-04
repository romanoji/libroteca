<?php

namespace RJozwiak\Libroteca\Domain\Model\BookCopy;

use RJozwiak\Libroteca\Domain\Model\AggregateRoot;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\Exception\BookCopyAlreadyLent;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;

class BookCopy implements AggregateRoot
{
    /** @var BookCopyID */
    private $id;

    /** @var BookID */
    private $bookID;

    /** @var bool */
    private $lent;

    /** @var null|ReaderID */
    private $readerID;

    /** @var \DateTimeImmutable */
    private $loanDueDate;

    /**
     * BookCopy constructor.
     * @param BookCopyID $copyID
     * @param BookID $bookID
     */
    public function __construct(BookCopyID $copyID, BookID $bookID)
    {
        $this->id = $copyID;
        $this->bookID = $bookID;
        $this->lent = false;
        $this->readerID = null;

        // TODO: event
    }

    /**
     * @param ReaderID $reader
     * @param \DateTimeImmutable $dueDate
     * @throws BookCopyAlreadyLent
     */
    public function lendTo(ReaderID $reader, \DateTimeImmutable $dueDate)
    {
        if ($this->lent) {
            throw new BookCopyAlreadyLent('Book copy is already lent.');
        }

        $this->readerID = $reader;
        $this->loanDueDate = $dueDate;
    }

    /**
     * @return BookCopyID
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return BookID
     */
    public function bookID()
    {
        return $this->bookID;
    }

    /**
     * @return bool
     */
    public function isLent()
    {
        return $this->lent;
    }

    public function readerID()
    {
        // TODO: write logic here
    }
}
