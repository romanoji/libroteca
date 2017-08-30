<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyRepository;
use RJozwiak\Libroteca\Domain\Model\BookCopy\Exception\BookCopyNotFoundException;

class UpdateBookCopyRemarksHandler implements CommandHandler
{
    /** @var BookCopyRepository */
    private $bookCopyRepository;

    /**
     * @param BookCopyRepository $bookCopyRepository
     */
    public function __construct(BookCopyRepository $bookCopyRepository)
    {
        $this->bookCopyRepository = $bookCopyRepository;
    }

    /**
     * @param UpdateBookCopyRemarks $command
     * @throws BookCopyNotFoundException
     */
    public function execute(UpdateBookCopyRemarks $command) : void
    {
        $bookCopyID = new BookCopyID($command->bookCopyID);

        $bookCopy = $this->bookCopyRepository->get($bookCopyID);
        $bookCopy->updateRemarks($command->remarks);

        $this->bookCopyRepository->save($bookCopy);
    }
}
