<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\BookLoan;

use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\DoctrineEntityID;

class DoctrineBookLoanID extends DoctrineEntityID
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'BookLoanID';
    }

    /**
     * @return string
     */
    protected function getNamespace(): string
    {
        return BookLoanID::class;
    }
}
