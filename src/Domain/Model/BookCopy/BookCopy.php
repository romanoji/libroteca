<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\BookCopy;

use RJozwiak\Libroteca\Domain\Model\Book\BookID;

class BookCopy
{
    /** @var BookCopyID */
    private $id;

    /** @var BookID */
    private $bookID;

    /** @var string */
    private $remarks;

    /**
     * BookCopy constructor.
     * @param BookCopyID $copyID
     * @param BookID $bookID
     * @param string $remarks
     */
    public function __construct(BookCopyID $copyID, BookID $bookID, string $remarks)
    {
        $this->id = $copyID;
        $this->bookID = $bookID;
        $this->setRemarks($remarks);
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
     * @return string
     */
    public function remarks()
    {
        return $this->remarks;
    }

    /**
     * @param string $remarks
     */
    public function setRemarks(string $remarks)
    {
        $this->remarks = $remarks;
    }
}
