<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Specification;


abstract class CompositeSpecification implements Specification
{
    /** @var Specification */
    protected $spec;

    /**
     * @param Specification $spec
     */
    public function __construct(Specification $spec)
    {
        $this->spec = $spec;
    }
}
