<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query;

use RJozwiak\Libroteca\Application\Query\ReaderQueryService;
use RJozwiak\Libroteca\Domain\Model\AggregateNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderNotFoundException;

class DoctrineReaderQueryService extends BaseDoctrineQueryService implements ReaderQueryService
{
    protected function tableName(): string
    {
        return 'readers';
    }

    protected function throwNotFoundException(): AggregateNotFoundException
    {
        return new ReaderNotFoundException();
    }
}
