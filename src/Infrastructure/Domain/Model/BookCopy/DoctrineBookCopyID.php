<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\BookCopy;

use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\DoctrineEntityID;

class DoctrineBookCopyID extends DoctrineEntityID
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'BookCopyID';
    }

    /**
     * @return string
     */
    protected function getNamespace(): string
    {
        return BookCopyID::class;
    }
}
