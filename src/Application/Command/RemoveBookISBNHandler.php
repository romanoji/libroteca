<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\Book\BookRepository;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanRepository;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAlreadyEndedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanNotFoundException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\EndingOverdueLoanWithoutRemarksException;

class RemoveBookISBNHandler implements CommandHandler
{
    /** @var BookRepository */
    private $bookRepository;

    /**
     * @param BookRepository $bookRepository
     */
    public function __construct(BookRepository $bookRepository) {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @param RemoveBookISBN $command
     * @throws \RJozwiak\Libroteca\Domain\Model\Book\Exception\BookNotFoundException
     */
    public function execute(RemoveBookISBN $command) : void
    {
        $bookID = new BookID($command->bookID);

        $book = $this->bookRepository->get($bookID);
        $book->removeISBN();

        $this->bookRepository->save($book);
    }
}
