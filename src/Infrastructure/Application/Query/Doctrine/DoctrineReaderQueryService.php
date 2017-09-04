<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use RJozwiak\Libroteca\Application\Query\ReaderQueryService;
use RJozwiak\Libroteca\Application\Query\Specification\ExpressionFactory;
use RJozwiak\Libroteca\Application\Query\Specification\Specification;
use RJozwiak\Libroteca\Domain\Model\AggregateNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderNotFoundException;

class DoctrineReaderQueryService extends BaseDoctrineDBALQueryService implements ReaderQueryService
{
    /** @var ExpressionFactory */
    private $expressionFactory;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ExpressionFactory $expressionFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ExpressionFactory $expressionFactory
    ) {
        parent::__construct($entityManager);

        $this->expressionFactory = $expressionFactory;
    }

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

    /**
     * @param null|Specification $criteria
     * @return array
     */
    public function getAllByCriteria(?Specification $criteria): array
    {
        $qb = $this->queryBuilder()
            ->select('*')
            ->from($this->tableName())
            ->addOrderBy('name')
            ->addOrderBy('surname');

        if ($criteria !== null) {
            $qb->where(
                $criteria->toExpression($this->expressionFactory)->value()
            );
        }

        return $qb->execute()->fetchAll();
    }
}
