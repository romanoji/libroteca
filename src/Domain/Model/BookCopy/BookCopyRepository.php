<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\BookCopy;

use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\Exception\BookCopyNotFoundException;

interface BookCopyRepository
{
    /**
     * @return BookCopyID
     */
    public function nextID() : BookCopyID;

    /**
     * @param BookCopy $bookCopy
     */
    public function add(BookCopy $bookCopy);

    /**
     * @param BookCopyID $id
     * @return BookCopy
     * @throws BookCopyNotFoundException
     */
    public function get(BookCopyID $id) : BookCopy;

    /**
     * @param BookID $bookID
     * @return BookCopy[]
     */
    public function findByBookID(BookID $bookID) : array;
}
