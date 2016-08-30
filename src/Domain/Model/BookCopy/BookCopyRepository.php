<?php

namespace RJozwiak\Libroteca\Domain\Model\BookCopy;

use RJozwiak\Libroteca\Domain\Model\Book\BookID;

interface BookCopyRepository
{
    /**
     * @return BookCopyID
     */
    public function nextID();

    /**
     * @param BookCopy $bookCopy
     */
    public function add(BookCopy $bookCopy);

    /**
     * @param BookCopyID $id
     * @return null|BookCopy
     */
    public function find(BookCopyID $id);

    /**
     * @param BookID $bookID
     * @return null|BookCopy
     */
    public function findByBookID(BookID $bookID);
}
