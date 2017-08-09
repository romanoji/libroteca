<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\DoctrineEntityID;

class DoctrineReaderID extends DoctrineEntityID
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'ReaderID';
    }

    /**
     * @return string
     */
    protected function getNamespace() : string
    {
        return ReaderID::class;
    }
}
