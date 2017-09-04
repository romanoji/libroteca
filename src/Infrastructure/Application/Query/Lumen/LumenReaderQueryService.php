<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen;

use RJozwiak\Libroteca\Application\Query\ReaderQueryService;
use RJozwiak\Libroteca\Application\Query\Specification\Specification;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderNotFoundException;
use RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen\Specification\ExpressionFactory;
use RJozwiak\Libroteca\Infrastructure\Persistence\Lumen;

class LumenReaderQueryService implements ReaderQueryService
{
    /** @var ExpressionFactory */
    private $expressionFactory;

    /**
     * @param ExpressionFactory $expressionFactory
     */
    public function __construct(ExpressionFactory $expressionFactory)
    {
        $this->expressionFactory = $expressionFactory;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return Lumen\Model\Reader::all()->toArray();
    }

    /**
     * @param int|string $readerID
     * @return array
     * @throws ReaderNotFoundException
     */
    public function getOne($readerID): array
    {
        $reader = Lumen\Model\Reader::find($readerID);

        if ($reader === null) {
            throw new ReaderNotFoundException();
        }

        return $reader->toArray();
    }

    /**
     * @param Specification $criteria
     * @return array
     */
    public function getAllByCriteria(?Specification $criteria): array
    {
        if ($criteria) {
            $expr = $criteria->toExpression($this->expressionFactory)->value();

            return Lumen\Model\Reader::where($expr)
                ->orderBy('name')
                ->orderBy('surname')
                ->get()
                ->toArray();
        } else {
            return Lumen\Model\Reader::all()->toArray();
        }
    }
}
