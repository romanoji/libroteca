<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\BookCopy;

use RJozwiak\Libroteca\Domain\Model\AggregateRoot;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;

class BookCopy extends AggregateRoot
{
    /** @var BookCopyID */
    private $id;

    /** @var BookID */
    private $bookID;

    /** @var string */
    private $remarks; // TODO: length limit

    // TODO: maybe embed BookLoan here?

    /**
     * @param BookCopyID $copyID
     * @param BookID $bookID
     * @param string $remarks
     */
    public function __construct(BookCopyID $copyID, BookID $bookID, string $remarks = '')
    {
        $this->id = $copyID;
        $this->bookID = $bookID;
        $this->updateRemarks($remarks);
    }

    /**
     * @return BookCopyID
     */
    public function id() : BookCopyID
    {
        return $this->id;
    }

    /**
     * @return BookID
     */
    public function bookID() : BookID
    {
        return $this->bookID;
    }

    /**
     * @return string
     */
    public function remarks() : string
    {
        return $this->remarks;
    }

    /**
     * @param string $remarks
     */
    public function updateRemarks(string $remarks) : void
    {
        $this->remarks = $remarks;
    }
}
