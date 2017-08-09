<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query;

use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderNotFoundException;

interface ReaderQueryService
{
    /**
     * @return array
     */
    public function getAll() : array;

    /**
     * @param int|string $readerID
     * @return array
     * @throws ReaderNotFoundException
     */
    public function getOne($readerID) : array;
}
