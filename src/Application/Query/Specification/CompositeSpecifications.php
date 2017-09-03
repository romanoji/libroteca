<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Specification;

abstract class CompositeSpecifications implements Specification
{
    /** @var Specification[] */
    protected $specs;

    /**
     * @param Specification[] $specs
     */
    public function __construct(Specification ...$specs)
    {
        $this->specs = $specs;
    }
}
