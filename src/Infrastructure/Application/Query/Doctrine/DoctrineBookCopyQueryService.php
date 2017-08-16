<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use RJozwiak\Libroteca\Application\Query\BookCopyQueryService;
use RJozwiak\Libroteca\Domain\Model\Book\Book;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\Book\Exception\BookNotFoundException;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopy;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoan;

class DoctrineBookCopyQueryService extends DoctrineQueryService implements BookCopyQueryService
{
    /**
     * @param int|string $bookID
     * @param bool $includeOngoingLoans
     * @return array
     * @throws BookNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAllByBook($bookID, bool $includeOngoingLoans = false): array
    {
        $this->assertBookExists($bookID);

        $bookCopies = $this->queryBuilder()
            ->select('bc')
            ->from(BookCopy::class, 'bc', 'bc.id')
            ->where('bc.bookID = :bookID')
            ->setParameter('bookID', new BookID($bookID))
            ->getQuery()
            ->getResult();

        $serializedBookCopies = $this->serializer->toArray($bookCopies);

        if ($includeOngoingLoans) {
            /** @var BookLoan[] $booksLoans */
            $booksLoans = $this->queryBuilder()
                ->select('bl')
                ->from(BookLoan::class, 'bl')
                ->where('bl.bookCopyID IN (:bookCopiesIDs)')
                ->setParameter('bookCopiesIDs', array_keys($bookCopies))
                ->getQuery()
                ->getResult();

            foreach ($booksLoans as $bookLoan) {
                $bookCopyID = $bookLoan->bookCopyID()->id();

                $serializedBookCopies[$bookCopyID]['ongoing_loan'] = $this->serializer->toArray($bookLoan);
            }
        }

        return array_values($serializedBookCopies);
    }

    /**
     * @param int|string $bookID
     * @throws BookNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function assertBookExists($bookID)
    {
        try {
            $this
                ->queryBuilder()
                ->select('b')
                ->from(Book::class, 'b')
                ->where('b.id = :id')
                ->setParameter('id', new BookID($bookID))
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            throw new BookNotFoundException();
        }
    }

    /**
     * @return QueryBuilder
     */
    protected function queryBuilder()
    {
        return $this->entityManager->createQueryBuilder();
    }
}
