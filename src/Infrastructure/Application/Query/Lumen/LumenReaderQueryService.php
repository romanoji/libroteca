<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen;

use RJozwiak\Libroteca\Application\Query\ReaderQueryService;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderNotFoundException;
use RJozwiak\Libroteca\Lumen;

class LumenReaderQueryService implements ReaderQueryService
{
    /**
     * @return array
     */
    public function getAll(): array
    {
        return Lumen\Models\Reader::all()->toArray();
    }

    /**
     * @param int|string $readerID
     * @return array
     * @throws ReaderNotFoundException
     */
    public function getOne($readerID): array
    {
        $reader = Lumen\Models\Reader::find($readerID);

        if ($reader === null) {
            throw new ReaderNotFoundException();
        }

        return $reader->toArray();
    }
}
