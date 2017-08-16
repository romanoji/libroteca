<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine;

use RJozwiak\Libroteca\Application\Query\ReaderQueryService;
use RJozwiak\Libroteca\Domain\Model\AggregateNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderNotFoundException;

class DoctrineReaderQueryService extends BaseDoctrineDBALQueryService implements ReaderQueryService
{
    /**
     * @return string
     */
    protected function tableName(): string
    {
        return 'readers';
    }

    /**
     * @return AggregateNotFoundException
     */
    protected function notFoundException(): AggregateNotFoundException
    {
        return new ReaderNotFoundException();
    }
}
