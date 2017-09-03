<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Book;

use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Domain\Model\Book\{
    Author, Book, BookID, BookRepository, Exception\BookNotFoundException, ISBN\ISBN, Title
};

class DoctrineBookRepository extends EntityRepository implements BookRepository
{
    /**
     * @return BookID
     */
    public function nextID(): BookID
    {
        return new BookID(Uuid::uuid4()->toString());
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function count(): int
    {
        $qb = $this->createQueryBuilder('b');

        return (int) $qb->select($qb->expr()->count('b'))->getQuery()->getSingleScalarResult();
    }

    /**
     * @param Book $book
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function save(Book $book)
    {
        $this->getEntityManager()->persist($book);
    }

    /**
     * @param BookID $id
     * @return Book
     * @throws BookNotFoundException
     */
    public function get(BookID $id): Book
    {
        $book = $this->find($id->id());

        if (!isset($book)) {
            throw new BookNotFoundException();
        }

        /** @var Book $book */
        return $book;
    }

    /**
     * @param ISBN $isbn
     * @return null|Book
     */
    public function findOneByISBN(ISBN $isbn): ?Book
    {
        return $this->findOneBy(['isbn' => $isbn]);
    }

    /**
     * @param Author $author
     * @param Title $title
     * @return Book[]
     */
    public function findByAuthorAndTitle(Author $author, Title $title): array
    {
        return $this->createQueryBuilder('b')
            ->where("in_array(:author, b.authors) = true")
            ->andWhere('b.title.title = :title')
            ->setParameters([
                'author' => $author,
                'title' => $title->title()
            ])
            ->getQuery()
            ->getResult();
    }
}
