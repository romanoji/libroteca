<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\Book\BookRepository;
use RJozwiak\Libroteca\Domain\Model\Book\Exception\BookNotFoundException;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopy;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyRepository;

class RegisterBookCopyHandler implements CommandHandler
{
    /** @var BookRepository */
    private $bookRepository;

    /** @var BookCopyRepository */
    private $bookCopyRepository;

    /**
     * @param BookRepository $bookRepository
     * @param BookCopyRepository $bookCopyRepository
     */
    public function __construct(
        BookRepository $bookRepository,
        BookCopyRepository $bookCopyRepository
    ) {
        $this->bookRepository = $bookRepository;
        $this->bookCopyRepository = $bookCopyRepository;
    }

    /**
     * @param RegisterBookCopy $command
     * @throws BookNotFoundException
     */
    public function execute(RegisterBookCopy $command): void
    {
        $bookCopyID = new BookCopyID($command->bookCopyID);
        $bookID = new BookID($command->bookID);

        $this->assertBookExists($bookID);

        $bookCopy = new BookCopy($bookCopyID, $bookID);
        $this->bookCopyRepository->save($bookCopy);
    }

    /**
     * @param BookID $bookID
     * @throws BookNotFoundException
     */
    public function assertBookExists(BookID $bookID)
    {
        $this->bookRepository->get($bookID);
    }
}
