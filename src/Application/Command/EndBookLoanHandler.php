<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanRepository;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAlreadyEndedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanNotFoundException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\EndingOverdueLoanWithoutRemarksException;

class EndBookLoanHandler implements CommandHandler
{
    /** @var BookLoanRepository */
    private $bookLoanRepository;

    /**
     * EndBookLoanHandler constructor.
     * @param BookLoanRepository $bookLoanRepository
     */
    public function __construct(BookLoanRepository $bookLoanRepository)
    {
        $this->bookLoanRepository = $bookLoanRepository;
    }

    /**
     * @param EndBookLoan $command
     * @throws BookLoanAlreadyEndedException
     * @throws EndingOverdueLoanWithoutRemarksException
     * @throws BookLoanNotFoundException
     */
    public function execute(EndBookLoan $command) : void
    {
        $bookLoanID = new BookLoanID($command->bookLoanID);

        $bookLoan = $this->bookLoanRepository->get($bookLoanID);

        $bookLoan->endLoan($command->endDate, $command->remarks);
        $this->bookLoanRepository->save($bookLoan);
    }
}
