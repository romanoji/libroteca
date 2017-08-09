<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\DoctrineEntityID;

class DoctrineBookID extends DoctrineEntityID
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'BookID';
    }

    /**
     * @return string
     */
    protected function getNamespace() : string
    {
        return BookID::class;
    }
}
