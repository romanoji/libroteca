<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopy;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyRepository;

class RegisterBookCopyHandler implements CommandHandler
{
    /** @var BookCopyRepository */
    private $bookCopyRepository;

    /**
     * RegisterBookCopyHandler constructor.
     * @param BookCopyRepository $bookCopyRepository
     */
    public function __construct(BookCopyRepository $bookCopyRepository)
    {
        $this->bookCopyRepository = $bookCopyRepository;
    }

    /**
     * @param RegisterBookCopy $command
     */
    public function execute(RegisterBookCopy $command) : void
    {
        $bookCopyID = new BookCopyID($command->bookCopyID);
        $bookID = new BookID($command->bookID);

        $bookCopy = new BookCopy($bookCopyID, $bookID);
        $this->bookCopyRepository->add($bookCopy);
    }
}
