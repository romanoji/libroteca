<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanRepository;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAlreadyEndedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanAlreadyProlongedException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanNotFoundException;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\ProlongOverdueBookLoanException;

class ProlongBookLoanHandler implements CommandHandler
{
    /** @var BookLoanRepository */
    private $bookLoanRepository;

    /**
     * @param BookLoanRepository $bookLoanRepository
     */
    public function __construct(BookLoanRepository $bookLoanRepository)
    {
        $this->bookLoanRepository = $bookLoanRepository;
    }

    /**
     * @param ProlongBookLoan $command
     * @throws BookLoanAlreadyEndedException
     * @throws BookLoanAlreadyProlongedException
     * @throws BookLoanNotFoundException
     * @throws ProlongOverdueBookLoanException
     * @throws \InvalidArgumentException
     */
    public function execute(ProlongBookLoan $command): void
    {
        $bookLoanID = new BookLoanID($command->bookLoanID);

        $bookLoan = $this->bookLoanRepository->get($bookLoanID);

        $bookLoan->prolongTo($command->newDueDate, $command->today);
        $this->bookLoanRepository->save($bookLoan);
    }
}
