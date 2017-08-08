<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\BookLoan;

use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\{
    BookLoan, BookLoanID, BookLoanRepository, Exception\BookLoanNotFoundException
};
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;

class DoctrineBookLoanRepository extends EntityRepository implements BookLoanRepository
{
    /**
     * @return BookLoanID
     */
    public function nextID(): BookLoanID
    {
        return new BookLoanID(Uuid::uuid4()->toString());
    }

    /**
     * @param BookLoan $bookLoan
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function save(BookLoan $bookLoan)
    {
        $this->getEntityManager()->persist($bookLoan);
    }

    /**
     * @param BookLoanID $id
     * @return BookLoan
     * @throws BookLoanNotFoundException
     */
    public function get(BookLoanID $id): BookLoan
    {
        $bookLoan = $this->find($id->id());

        if (!isset($bookLoan)) {
            throw new BookLoanNotFoundException();
        }

        /** @var BookLoan $bookLoan */
        return $bookLoan;
    }

    /**
     * @param BookCopyID $bookCopyID
     * @return null|BookLoan
     */
    public function findOngoingByBookCopyID(BookCopyID $bookCopyID): ?BookLoan
    {
        return $this->findOneBy(['bookCopyID' => $bookCopyID->id(), 'ended' => false]);
    }

    /**
     * @param ReaderID $readerID
     * @return BookLoan[]
     */
    public function findOngoingByReaderID(ReaderID $readerID): array
    {
        return $this->findBy(['readerID' => $readerID->id(), 'ended' => false]);
    }
}
