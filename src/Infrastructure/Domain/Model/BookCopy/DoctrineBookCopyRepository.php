<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\BookCopy;

use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\{
    BookCopy, BookCopyID, BookCopyRepository, Exception\BookCopyNotFoundException
};

class DoctrineBookCopyRepository extends EntityRepository implements BookCopyRepository
{
    /**
     * @return BookCopyID
     */
    public function nextID(): BookCopyID
    {
        return new BookCopyID(Uuid::uuid4()->toString());
    }

    /**
     * @param BookCopy $bookCopy
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function save(BookCopy $bookCopy)
    {
        $this->getEntityManager()->persist($bookCopy);
    }

    /**
     * @param BookCopyID $id
     * @return BookCopy
     * @throws BookCopyNotFoundException
     */
    public function get(BookCopyID $id): BookCopy
    {
        $bookCopy = $this->find($id->id());

        if (!isset($bookCopy)) {
            throw new BookCopyNotFoundException();
        }

        /** @var BookCopy $bookCopy */
        return $bookCopy;
    }

    /**
     * @param BookID $bookID
     * @return BookCopy[]
     */
    public function findByBookID(BookID $bookID): array
    {
        return $this->findBy(['bookID.id' => $bookID->id()]);
    }
}
