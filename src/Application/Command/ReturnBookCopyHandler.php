<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanRepository;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAlreadyEndedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\EndingOverdueLoanWithoutRemarksException;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderCannotReturnNotBorrowedBook;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderRepository;

class ReturnBookCopyHandler implements CommandHandler
{
    /** @var BookLoanRepository */
    private $bookLoanRepository;

    /**
     * ReturnBookCopyHandler constructor.
     * @param BookLoanRepository $bookLoanRepository
     */
    public function __construct(BookLoanRepository $bookLoanRepository) {
        $this->bookLoanRepository = $bookLoanRepository;
    }

    /**
     * @param ReturnBookCopy $command
     * @throws BookLoanAlreadyEndedException
     * @throws EndingOverdueLoanWithoutRemarksException
     */
    public function execute(ReturnBookCopy $command) : void
    {
        $bookLoanID = new BookLoanID($command->bookLoanID);

        $bookLoan = $this->bookLoanRepository->get($bookLoanID);

        $bookLoan->endLoan($command->endDate, $command->remarks);
        $this->bookLoanRepository->save($bookLoan);
    }
}
