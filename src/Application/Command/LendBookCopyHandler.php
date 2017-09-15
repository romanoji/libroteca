<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyRepository;
use RJozwiak\Libroteca\Domain\Model\BookCopy\Exception\BookCopyNotFoundException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanFactory;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanRepository;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookCopyAlreadyBorrowedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAttemptWhenHavingOverdueLoanException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\MaxOngoingLoansExceededException;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderRepository;

class LendBookCopyHandler implements CommandHandler
{
    /** @var ReaderRepository */
    private $readerRepository;

    /** @var BookCopyRepository */
    private $bookCopyRepository;

    /** @var BookLoanRepository */
    private $bookLoanRepository;

    /** @var BookLoanFactory */
    private $bookLoanFactory;

    /**
     * @param ReaderRepository $readerRepository
     * @param BookCopyRepository $bookCopyRepository
     * @param BookLoanRepository $bookLoanRepository
     * @param BookLoanFactory $bookLoanFactory
     */
    public function __construct(
        ReaderRepository $readerRepository,
        BookCopyRepository $bookCopyRepository,
        BookLoanRepository $bookLoanRepository,
        BookLoanFactory $bookLoanFactory
    ) {
        $this->readerRepository = $readerRepository;
        $this->bookCopyRepository = $bookCopyRepository;
        $this->bookLoanRepository = $bookLoanRepository;
        $this->bookLoanFactory = $bookLoanFactory;
    }

    /**
     * @param LendBookCopy $command
     * @throws BookCopyAlreadyBorrowedException
     * @throws BookCopyNotFoundException
     * @throws MaxOngoingLoansExceededException
     * @throws ReaderNotFoundException
     * @throws BookLoanAttemptWhenHavingOverdueLoanException
     */
    public function execute(LendBookCopy $command): void
    {
        $bookLoanID = new BookLoanID($command->bookLoanID());
        $readerID = new ReaderID($command->readerID());
        $bookCopyID = new BookCopyID($command->bookCopyID());

        $this->assertReaderExists($readerID);
        $this->assertBookCopyExists($bookCopyID);

        $bookLoan =
            $this->bookLoanFactory->create(
                $bookLoanID,
                $bookCopyID,
                $readerID,
                $command->dueDate(),
                $command->today()
            );
        $this->bookLoanRepository->save($bookLoan);
    }

    /**
     * @param ReaderID $readerID
     * @throws ReaderNotFoundException
     */
    private function assertReaderExists(ReaderID $readerID)
    {
        $this->readerRepository->get($readerID);
    }

    /**
     * @param BookCopyID $bookCopyID
     * @throws BookCopyNotFoundException
     */
    private function assertBookCopyExists(BookCopyID $bookCopyID)
    {
        $this->bookCopyRepository->get($bookCopyID);
    }
}
